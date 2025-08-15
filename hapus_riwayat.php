<?php
require 'function.php';
require 'ceklogin.php';

// Cek apakah ini POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: riwayat.php?error=invalid_request');
    exit;
}

$password = $_POST['password'] ?? '';

// Ambil password user dari database
$id_user = $_SESSION['id_user'];
$query = "SELECT password FROM users WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: riwayat.php?error=wrong_password');
    exit;
}

// Hapus riwayat
if (mysqli_query($conn, "DELETE FROM riwayat_stok")) {
    mysqli_query($conn, "ALTER TABLE riwayat_stok AUTO_INCREMENT = 1");
    header('Location: riwayat.php?status=cleared');
} else {
    header('Location: riwayat.php?error=delete_failed');
}
exit;
?>