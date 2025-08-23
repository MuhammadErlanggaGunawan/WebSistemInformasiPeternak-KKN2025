<?php
session_start();
$admin_id = $_SESSION['id_user'] ?? null;

// load koneksi
require 'config/db.php';

// --- PERBAIKAN KEAMANAN & LOGIKA LOGIN ---
// Proses Login User
if (isset($_POST['login'])) {
    // Input dari user bisa berupa username atau email
    $login_identifier = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Gunakan Prepared Statements yang memeriksa kedua kolom
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    // Bind input yang sama ke kedua placeholder
    mysqli_stmt_bind_param($stmt, "ss", $login_identifier, $login_identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($result);
    $row_count = mysqli_num_rows($result);

    // Cek apakah user ditemukan
    if ($row_count > 0) {
        // Verifikasi password
        if (password_verify($password, $user_data['password'])) {
            // Jika password cocok, set session
            $_SESSION['log'] = true;
            $_SESSION['id_user'] = $user_data['id_user'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['role'] = $user_data['role'];

            // Cek "Ingat Saya" untuk cookie
            if (isset($_POST['rememberme'])) {
                // Buat cookie
                setcookie('id', $user_data['id_user'], time() + (86400 * 30), "/"); // 86400 = 1 hari
                setcookie('key', hash('sha256', $user_data['username']), time() + (86400 * 30), "/");
            }

            // Redirect berdasarkan role
            if ($user_data['role'] == 'admin') {
                header('location:admin.php');
            } else {
                header('location:index.php');
            }
            exit;
        }
    }
    
    // Jika username/email atau password salah
    $_SESSION['login_error'] = "Username/Email atau password salah.";
    header('location:login.php');
    exit;
}
// --- AKHIR PERBAIKAN ---

?>