<?php
// File: api_detail.php (Untuk mengambil satu post berdasarkan ID)

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Koneksi Database
$conn = new mysqli("localhost", "root", "", "tugasMobile");
if($conn->connect_error){
    http_response_code(500); 
    echo json_encode(["message" => "Koneksi database gagal."]);
    exit(); 
}

// 1. Ambil ID dari URL (Query String)
$post_id = $_GET['id'] ?? null;

if (empty($post_id)) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "ID post wajib disediakan."]);
    $conn->close();
    exit();
}

// 2. Query Data dengan Prepared Statement
// PERBAIKAN: HANYA AMBIL id, title, content, dan created_at. userId DIHAPUS.
$query = "SELECT id, title, content, created_at FROM post WHERE id = ? LIMIT 1"; 
$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["message" => "Gagal menyiapkan query."]);
    $conn->close();
    exit();
}

// Ikat ID: 'i' untuk integer
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// 3. Ambil dan Format Hasil
if ($result->num_rows === 1) {
    $post_data = $result->fetch_assoc();
    
    http_response_code(200);
    
    // Kembalikan sebagai objek tunggal dalam key 'record'
    echo json_encode(["message" => "Data detail berhasil dimuat.", "record" => $post_data]);
} else {
    // 404 Not Found karena ID tidak ditemukan
    http_response_code(404); 
    echo json_encode(["message" => "Artikel tidak ditemukan.", "record" => null]);
}

$stmt->close();
$conn->close();
?>