<?php
// Tentukan API key rahasia Anda.
// Buatlah seunik mungkin!
define('VALID_API_KEY', '12345');

// Ambil API key yang dikirim oleh klien
$apiKey = null;
$headers = getallheaders();

if (isset($headers['X-API-Key'])) {
    $apiKey = $headers['X-API-Key'];
} elseif (isset($headers['x-api-key'])) { // Beberapa klien mengirim huruf kecil
    $apiKey = $headers['x-api-key'];
}

// Jika tidak ada key, atau key-nya salah, hentikan eksekusi
if ($apiKey === null || $apiKey !== VALID_API_KEY) {
    // Kirim respons "Unauthorized"
    http_response_code(401); 
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak. API Key tidak valid atau tidak ada.'
    ]);
    
    // Hentikan sisa skrip agar tidak berjalan
    die(); 
}

// Jika lolos, skrip akan lanjut berjalan seperti biasa...
?>