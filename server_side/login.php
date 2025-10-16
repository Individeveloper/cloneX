<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$conn = new mysqli("localhost", "root", "", "tugasMobile"); // Sesuaikan dengan nama database masing-masing
$data = json_decode(file_get_contents("php://input"), true); 

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM loginuser WHERE username = ? AND password = ?"); // Pastikan tabel dan kolom sesuai dengan database Anda 
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(200);
        echo json_encode(array(
            "message" => "Login successful",
            "success" => true,
            "username" => $username
        ));
    } else {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Invalid credentials",
            "success" => false
        ));
    }

    $stmt->close();
}

$conn->close();
?>