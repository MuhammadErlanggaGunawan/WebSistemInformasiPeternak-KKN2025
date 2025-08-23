<?php
// Aktifkan error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load koneksi database
require '../config/db.php';

// Set header untuk JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache');

// Query untuk mengambil semua data stok yang ada
$query = "
    SELECT 
        LOWER(j.nama_ternak) AS jenis,
        s.jumlah_betina,
        s.jumlah_jantan,
        s.jumlah
    FROM stok_ternak s
    JOIN jenis_ternak j ON s.id_jenis = j.id
    WHERE s.jumlah > 0
";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => true, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

// Siapkan struktur data akhir untuk dijumlahkan
$final_data = [
    'sapi' => ['total' => 0, 'total_betina' => 0, 'total_jantan' => 0],
    'kambing' => ['total' => 0, 'total_betina' => 0, 'total_jantan' => 0],
    'domba' => ['total' => 0, 'total_betina' => 0, 'total_jantan' => 0],
    'lainnya' => ['total' => 0, 'total_betina' => 0, 'total_jantan' => 0],
];

// Loop melalui hasil query dan jumlahkan datanya
while ($row = mysqli_fetch_assoc($result)) {
    $jenis_db = $row['jenis'];
    $betina = (int)$row['jumlah_betina'];
    $jantan = (int)$row['jumlah_jantan'];
    $total = (int)$row['jumlah'];

    if (strpos($jenis_db, 'sapi') !== false) {
        $final_data['sapi']['total_betina'] += $betina;
        $final_data['sapi']['total_jantan'] += $jantan;
        $final_data['sapi']['total'] += $total;
    } elseif (strpos($jenis_db, 'kambing') !== false) {
        $final_data['kambing']['total_betina'] += $betina;
        $final_data['kambing']['total_jantan'] += $jantan;
        $final_data['kambing']['total'] += $total;
    } elseif (strpos($jenis_db, 'domba') !== false) {
        $final_data['domba']['total_betina'] += $betina;
        $final_data['domba']['total_jantan'] += $jantan;
        $final_data['domba']['total'] += $total;
    } else {
        // Jika bukan ketiganya, masukkan ke 'Lainnya'
        $final_data['lainnya']['total_betina'] += $betina;
        $final_data['lainnya']['total_jantan'] += $jantan;
        $final_data['lainnya']['total'] += $total;
    }
}

// Kembalikan data sebagai objek JSON
echo json_encode($final_data, JSON_PRETTY_PRINT);

?>