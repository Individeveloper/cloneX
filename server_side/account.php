<?php
// file ini untuk menampilkan data. Kamu bisa membuat sebuah tabel baru di database kamu
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'auth.php';

$conn = new mysqli("localhost", "root", "", "tugasMobile");
if($conn->connect_error){
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Ambil parameter endpoint
$endpoint = $_GET['endpoint'] ?? 'users';

switch($endpoint) {
    case 'users':
        // Return user list but omit password for security
        $query = "SELECT id, username, email FROM loginuser";
        break;
        
    case 'posts':
        // Join posts with loginuser to include author info (username and email)
        $query = "SELECT p.*, l.username as author_name, l.email as author_email 
                  FROM post p 
                  INNER JOIN loginuser l ON p.username = l.username 
                  ORDER BY p.createdAt DESC";
        break;
        
    case 'user_posts':
        $username = $_GET['username'] ?? '';
        if (empty($username)) {
            http_response_code(400);
            echo json_encode(["error" => "Username parameter required"]);
            exit;
        }
    $query = "SELECT p.*, l.username as author_name, l.email as author_email 
          FROM post p 
          INNER JOIN loginuser l ON p.username = l.username 
          WHERE p.username = ? 
          ORDER BY p.createdAt DESC";
        break;
        
    case 'post_detail':
        $postId = $_GET['id'] ?? 0;
        if (empty($postId)) {
            http_response_code(400);
            echo json_encode(["error" => "Post ID parameter required"]);
            exit;
        }
    $query = "SELECT p.*, l.username as author_name, l.email as author_email 
          FROM post p 
          INNER JOIN loginuser l ON p.username = l.username 
          WHERE p.id = ?";
        break;
        
    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid endpoint. Available: users, posts, user_posts, post_detail"]);
        exit;
}

// Execute query dengan parameter jika diperlukan
if($endpoint == 'user_posts') {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif($endpoint == 'post_detail') {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
}

if($result->num_rows > 0){
    $data = $result->fetch_all(MYSQLI_ASSOC);
    http_response_code(200);
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>