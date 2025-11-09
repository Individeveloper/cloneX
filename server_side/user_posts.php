<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-Key, X-Username");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'auth.php';

$conn = new mysqli("localhost", "root", "", "tugasMobile");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit(0);
}

function getJsonBody() {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

// helper to get id from PATH_INFO or query
function getRequestedId() {
    if (!empty($_SERVER['PATH_INFO'])) {
        $parts = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        return isset($parts[0]) && is_numeric($parts[0]) ? intval($parts[0]) : null;
    }
    if (isset($_GET['id'])) return intval($_GET['id']);
    // try to parse from REQUEST_URI (fallback)
    $uri = $_SERVER['REQUEST_URI'];
    $m = preg_match('#user_posts.php/([0-9]+)#', $uri, $matches);
    if ($m) return intval($matches[1]);
    return null;
}

// GET user's posts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = $_GET['username'] ?? '';
    if (empty($username)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "username parameter required"]);
        $conn->close();
        exit(0);
    }

    $stmt = $conn->prepare("SELECT p.id, p.title, p.content, p.createdAt, p.username FROM post p WHERE p.username = ? ORDER BY p.createdAt DESC");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
    $stmt->close();
    $conn->close();
    exit(0);
}

// PUT update a post
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = getRequestedId();
    $data = getJsonBody();

    // Accept id in body as fallback
    if (!$id && isset($data['id'])) $id = intval($data['id']);

    $title = $data['title'] ?? null;
    $content = $data['content'] ?? null;
    $username = $data['username'] ?? ($_SERVER['HTTP_X_USERNAME'] ?? null);

    if (!$id) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "post id is required in URL or body"]);
        $conn->close();
        exit(0);
    }

    if (!$username) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "username is required in body or X-Username header"]);
        $conn->close();
        exit(0);
    }

    // Verify ownership
    $check = $conn->prepare("SELECT username FROM post WHERE id = ? LIMIT 1");
    $check->bind_param("i", $id);
    $check->execute();
    $r = $check->get_result();
    if ($r->num_rows !== 1) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Post not found"]);
        $check->close();
        $conn->close();
        exit(0);
    }
    $row = $r->fetch_assoc();
    if ($row['username'] !== $username) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "You are not the owner of this post"]);
        $check->close();
        $conn->close();
        exit(0);
    }
    $check->close();

    $fields = [];
    $types = '';
    $values = [];
    if ($title !== null) { $fields[] = 'title = ?'; $types .= 's'; $values[] = $title; }
    if ($content !== null) { $fields[] = 'content = ?'; $types .= 's'; $values[] = $content; }

    if (count($fields) === 0) {
        echo json_encode(["success" => true, "message" => "Nothing to update"]);
        $conn->close();
        exit(0);
    }

    $sql = "UPDATE post SET " . implode(', ', $fields) . " WHERE id = ?";
    $types .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to prepare statement"]);
        $conn->close();
        exit(0);
    }

    $bind_names[] = $types;
    for ($i=0; $i<count($values); $i++) {
        $bind_name = 'b' . $i;
        $$bind_name = $values[$i];
        $bind_names[] = &$$bind_name;
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Post updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to update post"]);
    }

    $stmt->close();
    $conn->close();
    exit(0);
}

// DELETE post
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = getRequestedId();
    if (!$id) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "post id required in URL or query (?id=)"]);
        $conn->close();
        exit(0);
    }

    $username = $_GET['username'] ?? ($_SERVER['HTTP_X_USERNAME'] ?? null);
    if (!$username) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "username is required as query param or X-Username header to authorize deletion"]);
        $conn->close();
        exit(0);
    }

    $check = $conn->prepare("SELECT username FROM post WHERE id = ? LIMIT 1");
    $check->bind_param("i", $id);
    $check->execute();
    $r = $check->get_result();
    if ($r->num_rows !== 1) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Post not found"]);
        $check->close();
        $conn->close();
        exit(0);
    }
    $row = $r->fetch_assoc();
    if ($row['username'] !== $username) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "You are not the owner of this post"]);
        $check->close();
        $conn->close();
        exit(0);
    }
    $check->close();

    $del = $conn->prepare("DELETE FROM post WHERE id = ?");
    $del->bind_param("i", $id);
    if ($del->execute()) {
        echo json_encode(["success" => true, "message" => "Post deleted"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to delete post"]);
    }

    $del->close();
    $conn->close();
    exit(0);
}

http_response_code(405);
echo json_encode(["success" => false, "message" => "Method not allowed"]);
$conn->close();
?>
