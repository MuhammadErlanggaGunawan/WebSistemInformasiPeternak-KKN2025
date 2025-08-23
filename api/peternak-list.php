<?php
// Aktifkan error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load koneksi database
require '../config/db.php';

// Set header untuk JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache');

// --- Logika Paginasi & Pencarian ---
$items_per_page = 9; // Tampilkan 9 peternak per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $items_per_page;

// Ambil dan siapkan parameter pencarian
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = '';
$search_param = '';
if (!empty($search_term)) {
    $search_sql = " AND u.username LIKE ?";
    $search_param = "%" . $search_term . "%";
}

// --- Hitung total peternak unik ---
$total_query_template = "
    SELECT COUNT(DISTINCT u.id_user) as total
    FROM stok_ternak s 
    JOIN users u ON s.id_user = u.id_user
    WHERE u.role != 'admin' AND s.jumlah > 0
    $search_sql
";
$stmt_total = mysqli_prepare($conn, $total_query_template);
if (!empty($search_term)) {
    mysqli_stmt_bind_param($stmt_total, "s", $search_param);
}
mysqli_stmt_execute($stmt_total);
$total_items = mysqli_stmt_get_result($stmt_total)->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// --- Query utama: ambil data stok per user ---
$query_template = "
    SELECT 
        u.id_user,
        u.username AS nama_peternak, 
        u.alamat, 
        u.no_hp,
        j.nama_ternak AS jenis_ternak,
        k.nama_kategori AS kategori_ternak,
        s.jumlah_betina,
        s.jumlah_jantan,
        s.jumlah AS jumlah_total
    FROM stok_ternak s
    JOIN users u ON s.id_user = u.id_user
    JOIN jenis_ternak j ON s.id_jenis = j.id
    LEFT JOIN kategori_ternak k ON s.id_kategori = k.id
    WHERE u.role != 'admin' AND s.jumlah > 0
    $search_sql
    ORDER BY u.username ASC
    LIMIT ? OFFSET ?
";

$stmt_data = mysqli_prepare($conn, $query_template);
if (!empty($search_term)) {
    mysqli_stmt_bind_param($stmt_data, "sii", $search_param, $items_per_page, $offset);
} else {
    mysqli_stmt_bind_param($stmt_data, "ii", $items_per_page, $offset);
}
mysqli_stmt_execute($stmt_data);
$result = mysqli_stmt_get_result($stmt_data);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id_user = $row['id_user'];

    if (!isset($data[$id_user])) {
        $data[$id_user] = [
            'nama_peternak' => $row['nama_peternak'],
            'alamat' => $row['alamat'],
            'no_hp' => $row['no_hp'],
            'ternak' => []
        ];
    }

    $data[$id_user]['ternak'][] = [
        'jenis' => $row['jenis_ternak'],
        'kategori' => $row['kategori_ternak'],
        'betina' => (int)$row['jumlah_betina'],
        'jantan' => (int)$row['jumlah_jantan'],
        'total'  => (int)$row['jumlah_total']
    ];
}

// --- Response final ---
$response = [
    'pagination' => [
        'total_items' => (int)$total_items,
        'total_pages' => (int)$total_pages,
        'current_page' => $page
    ],
    'data' => array_values($data)
];

echo json_encode($response, JSON_PRETTY_PRINT);
