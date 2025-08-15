<?php
require 'function.php';
require 'ceklogin.php';

// Set header untuk response JSON
header('Content-Type: application/json');

// Fungsi untuk mengirim response dan menghentikan script
function send_response($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response('error', 'Metode request tidak valid.');
}

$action = $_POST['action'] ?? '';
$admin_id = $_SESSION['id_user'] ?? 0;

if ($admin_id === 0) {
    send_response('error', 'Sesi admin tidak valid. Silakan login ulang.');
}

try {
    if ($action === 'insert') {
        $id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT);
        $id_jenis = filter_input(INPUT_POST, 'id_jenis', FILTER_VALIDATE_INT);
        $jumlah_betina = filter_input(INPUT_POST, 'jumlah_betina', FILTER_VALIDATE_INT) ?? 0;
        $jumlah_jantan = filter_input(INPUT_POST, 'jumlah_jantan', FILTER_VALIDATE_INT) ?? 0;
        $keterangan = trim($_POST['keterangan'] ?? '');
        $tanggal = $_POST['tanggal'] ? date('Y-m-d H:i:s', strtotime($_POST['tanggal'])) : date('Y-m-d H:i:s');
        $jumlah = $jumlah_betina + $jumlah_jantan;

        if (!$id_user || !$id_jenis) {
            send_response('error', 'User dan Jenis Ternak wajib diisi.');
        }

        // Insert ke stok_ternak
        $stmt = $conn->prepare("INSERT INTO stok_ternak (id_user, id_jenis, jumlah_betina, jumlah_jantan, jumlah, keterangan, tanggal_update) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiiss", $id_user, $id_jenis, $jumlah_betina, $jumlah_jantan, $jumlah, $keterangan, $tanggal);
        $stmt->execute();
        $id_stok = $conn->insert_id;

        // Insert ke riwayat
        $stmtRiwayat = $conn->prepare("INSERT INTO riwayat_stok (id_stok, id_user, id_jenis, jumlah, keterangan, aksi, admin_id) VALUES (?, ?, ?, ?, ?, 'insert', ?)");
        $stmtRiwayat->bind_param("iiiisi", $id_stok, $id_user, $id_jenis, $jumlah, $keterangan, $admin_id);
        $stmtRiwayat->execute();

        $_SESSION['success'] = "Stok berhasil ditambahkan!";
        send_response('success', 'Data berhasil ditambahkan.');

    } elseif ($action === 'update') {
        $id_stok = filter_input(INPUT_POST, 'id_stok', FILTER_VALIDATE_INT);
        $id_jenis = filter_input(INPUT_POST, 'id_jenis', FILTER_VALIDATE_INT);
        $jumlah_betina = filter_input(INPUT_POST, 'jumlah_betina', FILTER_VALIDATE_INT) ?? 0;
        $jumlah_jantan = filter_input(INPUT_POST, 'jumlah_jantan', FILTER_VALIDATE_INT) ?? 0;
        $keterangan = trim($_POST['keterangan'] ?? '');
        $tanggal = $_POST['tanggal'] ? date('Y-m-d H:i:s', strtotime($_POST['tanggal'])) : date('Y-m-d H:i:s');
        $jumlah = $jumlah_betina + $jumlah_jantan;

        if (!$id_stok || !$id_jenis) {
            send_response('error', 'ID Stok atau Jenis Ternak tidak valid.');
        }

        // Update stok
        $stmt = $conn->prepare("UPDATE stok_ternak SET id_jenis=?, jumlah_betina=?, jumlah_jantan=?, jumlah=?, keterangan=?, tanggal_update=? WHERE id_stok=?");
        $stmt->bind_param("iiiissi", $id_jenis, $jumlah_betina, $jumlah_jantan, $jumlah, $keterangan, $tanggal, $id_stok);
        $stmt->execute();

        // Ambil id_user untuk riwayat
        $stmtUser = $conn->prepare("SELECT id_user FROM stok_ternak WHERE id_stok = ?");
        $stmtUser->bind_param("i", $id_stok);
        $stmtUser->execute();
        $id_user = $stmtUser->get_result()->fetch_assoc()['id_user'];

        // Insert ke riwayat
        $stmtRiwayat = $conn->prepare("INSERT INTO riwayat_stok (id_stok, id_user, id_jenis, jumlah, keterangan, aksi, admin_id) VALUES (?, ?, ?, ?, ?, 'update', ?)");
        $stmtRiwayat->bind_param("iiiisi", $id_stok, $id_user, $id_jenis, $jumlah, $keterangan, $admin_id);
        $stmtRiwayat->execute();

        $_SESSION['success'] = "Stok berhasil diupdate!";
        send_response('success', 'Data berhasil diupdate.');

    } elseif ($action === 'delete') {
        $id_stok = filter_input(INPUT_POST, 'id_stok', FILTER_VALIDATE_INT);
        if (!$id_stok) {
            send_response('error', 'ID Stok tidak valid.');
        }

        // Ambil data sebelum dihapus untuk riwayat
        $stmtAmbil = $conn->prepare("SELECT id_user, id_jenis, jumlah, keterangan FROM stok_ternak WHERE id_stok=?");
        $stmtAmbil->bind_param("i", $id_stok);
        $stmtAmbil->execute();
        $data = $stmtAmbil->get_result()->fetch_assoc();

        if ($data) {
            // Simpan ke riwayat
            $stmtRiwayat = $conn->prepare("INSERT INTO riwayat_stok (id_stok, id_user, id_jenis, jumlah, keterangan, aksi, admin_id) VALUES (?, ?, ?, ?, ?, 'delete', ?)");
            $stmtRiwayat->bind_param("iiiisi", $id_stok, $data['id_user'], $data['id_jenis'], $data['jumlah'], $data['keterangan'], $admin_id);
            $stmtRiwayat->execute();
        }

        // Hapus data
        $stmt = $conn->prepare("DELETE FROM stok_ternak WHERE id_stok=?");
        $stmt->bind_param("i", $id_stok);
        $stmt->execute();

        $_SESSION['success'] = "Stok berhasil dihapus.";
        send_response('success', 'Data berhasil dihapus.');

    } else {
        send_response('error', 'Aksi tidak dikenali.');
    }
} catch (mysqli_sql_exception $e) {
    // Tangani error database
    send_response('error', 'Database Error: ' . $e->getMessage());
}
?>
