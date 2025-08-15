<?php
require 'function.php';
require 'ceklogin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Insert
    if ($action === 'insert') {
        $id_jenis = trim($_POST['id'] ?? '');
        $nama_ternak = trim($_POST['nama_ternak'] ?? '');
        $id_kategori = trim($_POST['id_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? null);
        $admin_id = $_SESSION['id_user'] ?? null;

        if (strlen($nama_ternak) < 3) {
            $_SESSION['error'] = "Nama ternak minimal 3 karakter!";
            header('Location: jenis.php');
            exit;
        }

        if (!$id_kategori) {
            $_SESSION['error'] = "Kategori harus dipilih!";
            header('Location: jenis.php');
            exit;
        }

        $query = "INSERT INTO jenis_ternak (nama_ternak, id_kategori, deskripsi) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            $_SESSION['error'] = "Gagal mempersiapkan query: " . $conn->error;
            header('Location: jenis.php');
            exit;
        }
        $stmt->bind_param("sss", $nama_ternak, $id_kategori,  $deskripsi);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Jenis ternak berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan jenis ternak: " . $stmt->error;
        }
        header('Location: jenis.php');
        exit;
    }
    elseif ($action === 'update') {
        // update / Edit
        $id_jenis = trim($_POST['id_jenis'] ?? '');
        $nama_ternak = trim($_POST['nama_ternak'] ?? '');
        $id_kategori = trim($_POST['id_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? null);
        $admin_id = $_SESSION['id_user'] ?? null;

        if (!$id_jenis) {
            $_SESSION['error'] = "ID jenis ternak tidak valid!";
            header('Location: jenis.php');
            exit;
        }

        if (strlen($nama_ternak) < 3) {
            $_SESSION['error'] = "Nama ternak minimal 3 karakter!";
            header('Location: jenis.php');
            exit;
        }

        if (!$id_kategori) {
            $_SESSION['error'] = "Kategori harus dipilih!";
            header('Location: jenis.php');
            exit;
        }

        $query = "UPDATE jenis_ternak SET nama_ternak=?, id_kategori=?, deskripsi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            $_SESSION['error'] = "Gagal mempersiapkan query: " . $conn->error;
            header('Location: jenis.php');
            exit;
        }
        $stmt->bind_param("sssi", $nama_ternak, $id_kategori, $deskripsi, $id_jenis);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Jenis ternak berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate jenis ternak: " . $stmt->error;
        }
        header('Location: jenis.php');
        exit;
    }
    elseif ($action === 'delete') {
        $id_jenis = intval($_POST['id_jenis'] ?? 0);
        if (!$id_jenis) {
            $_SESSION['error'] = "ID tidak valid.";
            header('Location: jenis.php'); exit;
        }
        $del = $conn->prepare("DELETE FROM jenis_ternak WHERE id=?");
        if ($del) {
            $del->bind_param("i", $id_jenis);
            if ($del->execute()) {
                $_SESSION['success'] = "Jenis berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus: " . $del->error;
            }
        } else {
            $_SESSION['error'] = "Query gagal: " . $conn->error;
        }
        header('Location: jenis.php'); exit;
    }

    // contoh update
    elseif ($action === 'update') {
        // ...lakukan update...
        if ($ok) {
            $_SESSION['success'] = "Jenis berhasil diupdate.";
        } else {
            $_SESSION['error'] = "Gagal update: " . ($stmt->error ?? 'unknown');
        }
        header('Location: jenis.php'); exit;
    }
}
