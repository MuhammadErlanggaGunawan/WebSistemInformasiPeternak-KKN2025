<?php
require 'function.php';
require 'ceklogin.php';

// Jika yang login adalah admin, arahkan ke halaman admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin.php');
    exit;
}

$id_user_login = $_SESSION['id_user'];

// Query untuk mengambil data stok HANYA milik user yang sedang login
// --- PERBAIKAN KEAMANAN ---
$query_template = "
    SELECT s.*, u.username, j.nama_ternak, j.id_kategori, k.nama_kategori
    FROM stok_ternak s
    JOIN users u ON s.id_user = u.id_user
    JOIN jenis_ternak j ON s.id_jenis = j.id
    JOIN kategori_ternak k ON s.id_kategori = k.id
    WHERE s.id_user = ?
    ORDER BY s.tanggal_update DESC
";

$stmt = mysqli_prepare($conn, $query_template);
mysqli_stmt_bind_param($stmt, "i", $id_user_login); // 'i' untuk integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
// --- AKHIR PERBAIKAN ---

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/png" href="./assets/img/log.png">
    <title>Profil Stok Saya - SITernak</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Tambahkan link font Material Symbols di sini -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .flash-alert-wrapper { position: fixed; top: 65px; left: 50%; transform: translateX(-50%); z-index: 1050; width: 400px; }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">SITernak</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <!-- Ganti ikon di baris ini -->
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="material-symbols-rounded align-middle">account_circle</i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link active" href="profil.php"><div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>Stok Saya</a>
                        <!-- PERUBAHAN 1: Tombol Kembali ke Beranda -->
                        <a class="nav-link" href="index.php"><div class="sb-nav-link-icon"><i class="fas fa-arrow-left"></i></div>Kembali</a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['username']); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="flash-alert-wrapper">
                        <?php
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.htmlspecialchars($_SESSION['success']).'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                            unset($_SESSION['success']);
                        }
                        ?>
                    </div>
                    <h1 class="mt-4">Stok Ternak Saya</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Ternak</th>
                                        <th>kategori</th>
                                        <th>Betina</th>
                                        <th>Jantan</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Tgl Update</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <tr data-id="<?= $row['id_stok'] ?>" data-jenis-id="<?= $row['id_jenis'] ?>" data-kategori-id="<?= $row['id_kategori'] ?>">
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['nama_ternak']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                                            <td><?= htmlspecialchars($row['jumlah_betina']); ?></td>
                                            <td><?= htmlspecialchars($row['jumlah_jantan']); ?></td>
                                            <td><?= htmlspecialchars($row['jumlah']); ?></td>
                                            <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                            <td><?= htmlspecialchars($row['tanggal_update']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?= $row['id_stok'] ?>">
                                                    <i class="fa fa-pen"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header bg-dark text-light">
                        <h5 class="modal-title" id="editModalLabel">Edit Stok Ternak</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_stok" id="idStokInput">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group mt-3">
                            <label for="idJenisSelect">Jenis Ternak</label>
                            <select name="id_jenis" id="idJenisSelect" class="form-control select2-ternak" required>
                                <?php
                                $query = "SELECT id, nama_ternak FROM jenis_ternak ORDER BY nama_ternak ASC";
                                $resultJenis = mysqli_query($conn, $query);
                                while($r = mysqli_fetch_assoc($resultJenis)) {
                                    echo '<option value="'.$r['id'].'">'.htmlspecialchars($r['nama_ternak']).'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="idKategoriSelect" class="form-label">Kategori Ternak</label>
                            <select name="id_kategori" id="idKategoriSelect" class="form-control select2-ternak" required>
                                <?php
                                $resultKategori = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori_ternak ORDER BY nama_kategori ASC");
                                while($kat = mysqli_fetch_assoc($resultKategori)){
                                    echo '<option value="'.$kat['id'].'">'.htmlspecialchars($kat['nama_kategori']).'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3"><label for="jumlahBetina" class="form-label">Jumlah Betina</label><input type="number" name="jumlah_betina" id="jumlahBetina" class="form-control" required></div>
                        <div class="form-group mb-3"><label for="jumlahJantan" class="form-label">Jumlah Jantan</label><input type="number" name="jumlah_jantan" id="jumlahJantan" class="form-control" required></div>
                        <div class="form-group mb-3"><label for="keteranganTextarea" class="form-label">Keterangan</label><textarea name="keterangan" id="keteranganTextarea" class="form-control" rows="3"></textarea></div>
                        <div class="form-group mb-3"><label for="tanggalInput" class="form-label">Tanggal</label><input type="datetime-local" name="tanggal" id="tanggalInput" class="form-control" required></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.select2-ternak').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#editModal')
        });

        $('#editModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id_stok = button.data('id');
            const row = $(`tr[data-id='${id_stok}']`);
            
            const modal = $(this);
            modal.find('#idStokInput').val(id_stok);
            modal.find('#idJenisSelect').val(row.data('jenis-id')).trigger('change');
            modal.find('#jumlahBetina').val(row.find('td:eq(2)').text());
            modal.find('#jumlahJantan').val(row.find('td:eq(3)').text());
            modal.find('#keteranganTextarea').val(row.find('td:eq(5)').text());
            const rawDate = row.find('td:eq(6)').text().trim();
            if (rawDate) {
                modal.find('#tanggalInput').val(rawDate.replace(" ", "T").slice(0, 16));
            }
        });

        // AJAX form submission
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            $.post('proses_stok.php', $(this).serialize())
                .done(function(response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                })
                .fail(function() { alert('Request ke server gagal.'); });
        });
    });
    </script>
    <script>
    // Data semua jenis ternak
    const allJenisTernak = {};
    <?php
    $resultAllJenis = mysqli_query($conn, "
        SELECT id, nama_ternak, id_kategori 
        FROM jenis_ternak 
        ORDER BY nama_ternak ASC
    ");
    while($j = mysqli_fetch_assoc($resultAllJenis)){
        echo "allJenisTernak[{$j['id']}] = {id: {$j['id']}, nama: '" . addslashes($j['nama_ternak']) . "', id_kategori: {$j['id_kategori']}};";
    }
    ?>

    function loadJenisByKategori(kategoriId) {
        const select = $('#idJenisSelect');
        select.empty(); // Kosongkan dropdown
        
        Object.values(allJenisTernak).forEach(jenis => {
            if (jenis.id_kategori == kategoriId) {
                select.append(`<option value="${jenis.id}">${jenis.nama}</option>`);
            }
        });
        
        select.trigger('change'); // Refresh Select2
    }

    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2-ternak').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#editModal')
        });

        // Event: Modal muncul
        $('#editModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id_stok = button.data('id');
            const row = $(`tr[data-id='${id_stok}']`);
            
            const modal = $(this);
            modal.find('#idStokInput').val(id_stok);
            
            const id_kategori = row.data('kategori-id');
            modal.find('#idKategoriSelect').val(id_kategori);
            
            // Isi dropdown jenis ternak berdasarkan kategori
            loadJenisByKategori(id_kategori);
            
            // Set nilai jenis ternak setelah dropdown terisi
            setTimeout(() => {
                modal.find('#idJenisSelect').val(row.data('jenis-id')).trigger('change');
            }, 300);

            modal.find('#jumlahBetina').val(row.find('td:eq(3)').text());
            modal.find('#jumlahJantan').val(row.find('td:eq(4)').text());
            modal.find('#keteranganTextarea').val(row.find('td:eq(6)').text());
            const rawDate = row.find('td:eq(7)').text().trim();
            if (rawDate) {
                modal.find('#tanggalInput').val(rawDate.replace(" ", "T").slice(0, 16));
            }
        });

        // Ganti dropdown jenis saat kategori berubah
        $('#idKategoriSelect').on('change', function() {
            const kategoriId = $(this).val();
            if (kategoriId) {
                loadJenisByKategori(kategoriId);
            }
        });

        // Submit form
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            $.post('proses_stok.php', $(this).serialize())
                .done(function(response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                })
                .fail(function() { alert('Request ke server gagal.'); });
        });
    });
    </script>
</body>
</html>