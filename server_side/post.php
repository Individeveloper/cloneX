<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'auth.php';

$conn = new mysqli("localhost", "root", "", "tugasMobile");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $username = $data['username'] ?? '';
    
    if (empty($title) || empty($content) || empty($username)) {
        http_response_code(400);
        echo json_encode([
            "success" => false, 
            "message" => "Title, content, and username are required"
        ]);
        exit(0);
    }
    
    // Insert post ke database
    $stmt = $conn->prepare("INSERT INTO post (username, title, content, createdAt) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $title, $content);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "success" => true, 
            "message" => "Post created successfully",
            "post_id" => $conn->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false, 
            "message" => "Failed to create post: " . $stmt->error
        ]);
    }
    
    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
}

$conn->close();
?>