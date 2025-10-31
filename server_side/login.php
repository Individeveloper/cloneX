<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-Key");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'auth.php';

$conn = new mysqli("localhost", "root", "", "tugasMobile"); 
$data = json_decode(file_get_contents("php://input"), true);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    // Only select username and hashed password
    $stmt = $conn->prepare("SELECT username, password FROM loginuser WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Direct password comparison (not recommended for production)
        if ($password === $user['password']) {
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