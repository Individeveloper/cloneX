<?php
// 1. Atur Header (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 2. Koneksi Database (Ganti dengan kredensial Anda)
$conn = new mysqli("localhost", "root", "", "tugasMobile");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Koneksi database gagal."]);
    exit(0);
}

// 3. Terima Data JSON dari Frontend
$data = json_decode(file_get_contents("php://input"), true) ?? [];

$user_id_raw = $data['userId'] ?? $data['user_id'] ?? null; // terima kedua format kunci
$title = isset($data['title']) ? trim($data['title']) : null;
$content = isset($data['content']) ? trim($data['content']) : null;

// 4. Validasi Input
$user_id = filter_var($user_id_raw, FILTER_VALIDATE_INT);

if (!$user_id || $user_id <= 0 || $title === '' || $content === '') {
    http_response_code(400);
    echo json_encode([
        "message" => "Field userId, title, dan content wajib diisi dengan benar.",
        "success" => false
    ]);
    exit(0);
}

// PASTIKAN KOLOM 'title' ADA di tabel 'post'
$query = "INSERT INTO post (userId, title, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);

// Ikat parameter: s (user_id), s (title), s (content)
$stmt->bind_param("sss", $user_id, $title, $content);

if ($stmt->execute()) {
    http_response_code(201); // Created
    echo json_encode(array(
        "message" => "Kutipan berhasil disimpan.",
        "success" => true,
        "insert_id" => $conn->insert_id // ID baris yang baru dibuat
    ));
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(array(
        "message" => "Gagal menyimpan data: " . $stmt->error,
        "success" => false
    ));
}

$stmt->close();
$conn->close();
?>