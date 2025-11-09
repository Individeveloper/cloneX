<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-Key");

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

// Helper to read JSON body
function getJsonBody() {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

// Handle GET: return single user by username
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = $_GET['username'] ?? null;
    if (empty($username)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "username parameter is required"]);
        $conn->close();
        exit(0);
    }

    $stmt = $conn->prepare("SELECT * FROM loginuser WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
    }

    $stmt->close();
    $conn->close();
    exit(0);
}

// Handle PUT for profile update or password change
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // detect if request is for password change by checking the REQUEST_URI
    $uri = $_SERVER['REQUEST_URI'];
    $isPasswordChange = (strpos($uri, '/password') !== false || strpos($uri, 'password') !== false);

    $data = getJsonBody();

    if ($isPasswordChange) {
        // Change password: expect username, currentPassword, newPassword
        $username = $data['username'] ?? '';
        $current = $data['currentPassword'] ?? '';
        $new = $data['newPassword'] ?? '';

        if (!$username || !$current || !$new) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "username, currentPassword and newPassword are required"]);
            $conn->close();
            exit(0);
        }

        $stmt = $conn->prepare("SELECT password FROM loginuser WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows !== 1) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "User not found"]);
            $stmt->close();
            $conn->close();
            exit(0);
        }

        $row = $res->fetch_assoc();
        $stored = $row['password'];

        // Compare directly (note: passwords currently stored plain-text in this project)
        if ($current !== $stored) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Current password is incorrect"]);
            $stmt->close();
            $conn->close();
            exit(0);
        }

        $stmt->close();

        $upd = $conn->prepare("UPDATE loginuser SET password = ? WHERE username = ?");
        $upd->bind_param("ss", $new, $username);
        if ($upd->execute()) {
            echo json_encode(["success" => true, "message" => "Password updated"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to update password"]);
        }
        $upd->close();
        $conn->close();
        exit(0);
    }

    // Otherwise handle profile update: expect username and fields to update (email, optionally new_username)
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? null;
    $newUsername = $data['new_username'] ?? null;

    if (!$username) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "username is required"]);
        $conn->close();
        exit(0);
    }

    // If new username provided, ensure uniqueness
    if ($newUsername && $newUsername !== $username) {
        $check = $conn->prepare("SELECT id FROM loginuser WHERE username = ? LIMIT 1");
        $check->bind_param("s", $newUsername);
        $check->execute();
        $r = $check->get_result();
        if ($r->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "Username already taken"]);
            $check->close();
            $conn->close();
            exit(0);
        }
        $check->close();
    }

    // Build update dynamically
    $fields = [];
    $types = '';
    $values = [];
    if ($email !== null) { $fields[] = 'email = ?'; $types .= 's'; $values[] = $email; }
    if ($newUsername) { $fields[] = 'username = ?'; $types .= 's'; $values[] = $newUsername; }

    if (count($fields) === 0) {
        echo json_encode(["success" => true, "message" => "Nothing to update"]);
        $conn->close();
        exit(0);
    }

    $sql = "UPDATE loginuser SET " . implode(', ', $fields) . " WHERE username = ?";
    $types .= 's';
    $values[] = $username;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to prepare statement"]);
        $conn->close();
        exit(0);
    }

    // bind params dynamically
    $bind_names[] = $types;
    for ($i=0; $i<count($values); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $values[$i];
        $bind_names[] = &$$bind_name;
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to update profile"]);
    }

    $stmt->close();
    $conn->close();
    exit(0);
}

// Method not allowed
http_response_code(405);
echo json_encode(["success" => false, "message" => "Method not allowed"]);
$conn->close();
?>
