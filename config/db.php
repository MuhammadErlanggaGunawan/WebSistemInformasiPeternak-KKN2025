<?php
$conn = mysqli_connect("localhost", "root", "", "stokternak");

if (!$conn){
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Koneksi database gagal']);
    exit();
}
?>