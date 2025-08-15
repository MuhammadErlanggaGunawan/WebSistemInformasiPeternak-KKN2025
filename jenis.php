<?php
require 'function.php';
require 'ceklogin.php';

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$query = "
    SELECT j.id AS id_jenis, j.nama_ternak, k.id AS id_kategori, k.nama_kategori, j.deskripsi
    FROM jenis_ternak j
    JOIN kategori_ternak k ON j.id_kategori = k.id
";

$resultJenis = mysqli_query($conn, $query);

if (!$resultJenis) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Jenis Ternak</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding-left: 2px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: relative;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            font-size: 1em;
            font-weight: 400;
        }
        
        table td, table th {
            vertical-align: middle !important;
        }
        
        .flash-alert-wrapper {
            position: fixed;
            top: 56px; 
            left: 0;
            right: 0;
            z-index: 1050;
            padding: 0 1rem;
            display: flex;
            justify-content: center;
            pointer-events: none; 
        }
        .flash-alert {
            max-width: 400px;
            width: 100%;
            margin: 0;
            pointer-events: auto; 
        }
        /* navbar / hamburger alignment fixes */
        .sb-topnav.navbar {
            height: 56px;
            min-height: 56px;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }
        .sb-topnav .navbar-brand {
            display: inline-flex;
            align-items: center;
            margin: 0;
            padding: 0;
            line-height: 1;
        }
        .sb-topnav .navbar-toggler,
        .sb-topnav .btn#sidebarToggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0.35rem 0.6rem;
            border: none;
            background: transparent;
            height: 40px;
            color: rgba(255,255,255,0.9);
        }
        .sb-topnav .navbar-toggler + .navbar-brand,
        .sb-topnav .btn#sidebarToggle + .navbar-brand {
            margin-left: 0.5rem;
        }
        .sb-topnav { z-index: 1100; }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php if (!empty($success)): ?>
        <div class="flash-alert-wrapper">
            <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        </div>
        <?php elseif (!empty($error)): ?>
        <div class="flash-alert-wrapper">
            <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        </div>
        <?php endif; ?>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">    
            <button class="btn btn-link btn-sm order-1 order-lg-0 navbar-toggler" id="sidebarToggle" type="button"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand ml-2" href="admin.php">Dashboard Admin</a>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"></div>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box "></i></div>
                                Stok Ternak
                            </a>
                            <a class="nav-link" href="jenis.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                Jenis Ternak
                            </a>
                            <a class="nav-link" href="user.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="riwayat.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                                Riwayat 
                            </a>
                            <br>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= htmlspecialchars($_SESSION['email'] ?? 'Guest'); ?>
                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4 mb-4">Jenis Ternak</h1>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-end">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    <i class="fa fa-plus me-2"></i>
                                    Tambah
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Ternak</th>
                                                <th>Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?= $no = 1;?>
                                            <?php while ($row = mysqli_fetch_assoc($resultJenis)):?>
                                                <tr data-jenis-id="<?= $row['id_jenis']?>"
                                                data-kategori-id="<?= $row['id_kategori']?>"
                                                data-kategori-nama="<?= $row['nama_kategori'] ?>">

                                                    <td><?= $no++ ?> </td>
                                                    <td><?= htmlspecialchars($row['nama_ternak']) ?></td>
                                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                                    <td class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-dark m-2" data-toggle="modal" data-target="#myModal" onclick="editJenis(<?= $row['id_jenis'] ?>)">
                                                            <i class="fa fa-pen me-2"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger" onclick="deleteJenis(<?= $row['id_jenis'] ?>)">
                                                            <i class="fa fa-trash me-2"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="proses_jenis.php" method="POST">
                        <input type="hidden" name="id_jenis" id="idJenisInput">
                        <!-- Modal Header -->
                        <div class="modal-header bg-dark text-light">
                            <h4 class="modal-title">Tambah Jenis Ternak</h4>
                            <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="namaTernak">Nama Ternak</label>
                                <input type="text" name="nama_ternak" id="namaTernak" class="form-control" required minlength="3" placeholder="Masukkan nama ternak">
                            </div>

                            <div class="form-group mt-3">
                                <label for="kategoriSelect">Kategori Peternak</label>
                                <select name="id_kategori" id="kategoriSelect" class="form-control select2-kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    $kategoriQuery = "SELECT id, nama_kategori FROM kategori_ternak ORDER BY nama_kategori ASC";
                                    $kategoriResult = mysqli_query($conn, $kategoriQuery);
                                    while($kategori = mysqli_fetch_assoc($kategoriResult)) {
                                        echo '<option value="'.htmlspecialchars($kategori['id']).'">'.htmlspecialchars($kategori['nama_kategori']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mt-3">
                                <label for="deskripsiTextarea">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsiTextarea" class="form-control" rows="3" placeholder="Deskripsi singkat tentang jenis ternak"></textarea>
                            </div>
                        </div>

                        
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                            <button type="submit" name="action" id="modalSubmitBtn" class="btn btn-primary"  value="insert">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="js/jenis.js"></script>
    </body>
</html>