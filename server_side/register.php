<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$conn = new mysqli("localhost", "root", "", "tugasMobile");

if($conn->connect_error){
    http_response_code(500);
    echo json_encode(["message" => "Koneksi database gagal."]);
    exit(0);
}

$data = json_decode(file_get_contents("php://input"), true);

$username = isset($data['username']) ? trim($data['username']) : null;
$password = isset($data['password']) ? trim($data['password']) : null;
$email = isset($data['email']) ? trim($data['email']) : null;

if ($username === '' || $password === '' || $email === '') {
    http_response_code(400);
    echo json_encode([
        "message" => "Field username dan password wajib diisi dengan benar.",
        "success" => false
    ]);
    exit(0);
}

$query = "INSERT INTO loginuser (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);

$stmt->bind_param("sss", $username, $password, $email);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(array(
        "message" => "Registrasi berhasil.",
        "success" => true,
        "insert_id" => $conn->insert_id
    ));
} else {
    http_response_code(500);
    echo json_encode(array(
        "message" => "Gagal menyimpan data: " . $stmt->error,
        "success" => false
    ));
}

$stmt->close();
$conn->close();
?>