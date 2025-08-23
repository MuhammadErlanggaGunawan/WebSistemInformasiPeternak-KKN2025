<?php
// Aktifkan error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load koneksi database
require '../config/db.php';

// Set header untuk JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache');

// Query untuk mengambil semua jenis ternak yang BUKAN sapi, kambing, atau domba
$query = "
    SELECT 
        j.nama_ternak,
        SUM(s.jumlah) AS jumlah_total
    FROM stok_ternak s
    JOIN jenis_ternak j ON s.id_jenis = j.id
    WHERE LOWER(j.nama_ternak) NOT IN ('sapi', 'kambing', 'domba') AND s.jumlah > 0
    GROUP BY j.nama_ternak
    ORDER BY j.nama_ternak ASC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => true, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['jumlah_total'] = (int)$row['jumlah_total'];
    $data[] = $row;
}

// Kembalikan data sebagai JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>