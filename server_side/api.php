<?php
// Kode di halaman ini berfungsi untuk menampilkan data sesuai dengan database/table yang kamu buat
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

$conn = new mysqli("localhost", "root", "", "tugasMobile");
if($conn->connect_error){
    die("Koneksi Gagal: " . $conn->connect_error);
}

$query = "SELECT username, password FROM loginuser";
$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows > 0){
    $data = $result->fetch_all(MYSQLI_ASSOC);
    http_response_code(200);
    echo json_encode($data);
}else{
    http_response_code(404);
    echo json_encode([]);
}

$stmt->close();
$conn->close();


?>