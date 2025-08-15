<?php
require 'function.php';
require 'ceklogin.php';

$role = $_SESSION['role'] ?? null;
if ($role != 'admin') {
    $_SESSION['error'] = "Akses ditolak! Halaman ini hanya untuk admin.";
    header('Location: index.php');
    exit;
}

$action = $_POST['action'] ?? (isset($_POST['tambah']) ? 'insert' : '');

if ($action === 'insert') {
    // existing insert logic (sanitized + prepared)
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_plain = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role_in = trim($_POST['role'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');

    // basic validation (kept short)
    if (empty($nama_lengkap) || empty($email) || empty($password_plain) || empty($password_confirm) || empty($alamat) || empty($no_hp) || empty($role_in)) {
        $_SESSION['error'] = "Semua field harus diisi!";
        header('Location: tambah_user.php');
        exit;
    }
    if ($password_plain !== $password_confirm) {
        $_SESSION['error'] = "Konfirmasi password tidak sama!";
        header('Location: tambah_user.php');
        exit;
    }
    // cek email unik
    $check = $conn->prepare("SELECT id_user FROM users WHERE email=? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header('Location: tambah_user.php');
        exit;
    }
    $check->close();

    $username = strtolower(str_replace(' ', '', $nama_lengkap));
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, email, password, alamat, no_hp, role) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $nama_lengkap, $username, $email, $password_hash, $alamat, $no_hp, $role_in);
    if ($stmt->execute()) {
        $_SESSION['success'] = "User berhasil dibuat!";
        header('Location: user.php');
        exit;
    } else {
        $_SESSION['error'] = "Gagal membuat user: " . $stmt->error;
        header('Location: tambah_user.php');
        exit;
    }
}

if ($action === 'update') {
    $id_user = intval($_POST['id_user'] ?? 0);
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_plain = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role_in = trim($_POST['role'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');

    if (!$id_user) {
        $_SESSION['error'] = "ID user tidak valid!";
        header('Location: user.php');
        exit;
    }
    if (empty($nama_lengkap) || empty($email) || empty($alamat) || empty($no_hp) || empty($role_in)) {
        $_SESSION['error'] = "Semua field wajib diisi (kecuali password jika tidak ingin diubah).";
        header('Location: tambah_user.php?id=' . $id_user);
        exit;
    }
    // cek email unik (kecuali untuk user ini)
    $check = $conn->prepare("SELECT id_user FROM users WHERE email=? AND id_user<>? LIMIT 1");
    $check->bind_param("si", $email, $id_user);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email sudah digunakan oleh user lain!";
        header('Location: tambah_user.php?id=' . $id_user);
        exit;
    }
    $check->close();

    // siapkan query update (jika password diisi -> update password juga)
    if (!empty($password_plain) || !empty($password_confirm)) {
        if ($password_plain !== $password_confirm) {
            $_SESSION['error'] = "Konfirmasi password tidak sama!";
            header('Location: tambah_user.php?id=' . $id_user);
            exit;
        }
        $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, email=?, password=?, alamat=?, no_hp=?, role=? WHERE id_user=?");
        $stmt->bind_param("ssssssi", $nama_lengkap, $email, $password_hash, $alamat, $no_hp, $role_in, $id_user);
    } else {
        $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, email=?, alamat=?, no_hp=?, role=? WHERE id_user=?");
        $stmt->bind_param("sssssi", $nama_lengkap, $email, $alamat, $no_hp, $role_in, $id_user);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "User berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate user: " . $stmt->error;
    }
    header('Location: user.php');
    exit;
}

if ($action === 'delete') {
    $id_user = intval($_POST['id_user'] ?? 0);
    if (!$id_user) {
        $_SESSION['error'] = "ID user tidak valid!";
        header('Location: user.php');
        exit;
    }
    // jangan hapus diri sendiri
    if ($id_user == ($_SESSION['id_user'] ?? 0)) {
        $_SESSION['error'] = "Tidak dapat menghapus akun yang sedang login.";
        header('Location: user.php');
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id_user=?");
    $stmt->bind_param("i", $id_user);
    if ($stmt->execute()) {
        $_SESSION['success'] = "User berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus user: " . $stmt->error;
    }
    header('Location: user.php');
    exit;
}
?>