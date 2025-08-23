<?php
require 'function.php';
require 'ceklogin.php';

// Query gabungan stok ternak + user + jenis ternak
$query = "
    SELECT s.id_stok, s.id_user, j.id AS id_jenis, k.id AS id_kategori, u.username, j.nama_ternak, k.nama_kategori,
       s.jumlah_betina, s.jumlah_jantan, s.jumlah,
       s.keterangan, s.tanggal_update
    FROM stok_ternak s
    JOIN users u ON s.id_user = u.id_user
    JOIN jenis_ternak j ON s.id_jenis = j.id
    JOIN kategori_ternak k ON s.id_kategori = k.id
    ORDER BY s.tanggal_update DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
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
        <title>Dashboard Admin</title>
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
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
        }

        .flash-alert {
            max-width: 1140px;
            width: auto;
            margin: 0 auto;
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
                            <a class="nav-link" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
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
                        <div class="flash-alert-wrapper">
                            <?php
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                                echo htmlspecialchars($_SESSION['success']);
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                                echo '</div>';
                                unset($_SESSION['success']); // Hapus pesan agar tidak muncul lagi
                            }
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                echo htmlspecialchars($_SESSION['error']);
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                                echo '</div>';
                                unset($_SESSION['error']); // Hapus pesan agar tidak muncul lagi
                            }
                            ?>
                        </div>
                        <h1 class="mt-4 mb-4">Stok Ternak</h1>
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
                                                <th>Nama User</th>
                                                <th>Jenis Ternak</th>
                                                <th>Kategori</th>
                                                <th>Betina</th>
                                                <th>Jantan</th>
                                                <th>Jumlah</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                        <?= $no = 1;?>
                                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                            <tr data-id="<?= $row['id_stok'] ?>" data-user-id="<?= $row['id_user'] ?>" data-jenis-id="<?= $row['id_jenis'] ?>" data-kategori-id="<?= $row['id_kategori'] ?>">
                                                <td><?= $no++ ?> </td>
                                                <td><?= htmlspecialchars($row['username']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_ternak']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                                <td><?= (int)$row['jumlah_betina']; ?></td>
                                                <td><?= (int)$row['jumlah_jantan']; ?></td>
                                                <td><?= (int)$row['jumlah']; ?></td>
                                                <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                                <td><?= htmlspecialchars($row['tanggal_update']); ?></td>
                                                <td class="d-flex justify-content-center align-items-center">
                                                    <button type="button" class="btn btn-dark m-2"
                                                    data-toggle="modal" data-target="#myModal" data-action="edit" data-id="<?= $row['id_stok'] ?>">
                                                        <i class="fa fa-pen me-2"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger"
                                                    onclick="deleteStok(<?= $row['id_stok'] ?>)">
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
                    <form action="proses_stok.php" method="POST">
                        <input type="hidden" name="id_stok" id="idStokInput">
                        <!-- Modal Header -->
                        <div class="modal-header bg-dark text-light">
                            <h4 class="modal-title">Tambah Stok</h4>
                            <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="idUserSelect">Nama User</label>
                                <select name="id_user" id="idUserSelect" class="form-control select2-users" required>
                                    <option value="">Pilih Nama User</option>
                                    <?php
                                    // Ambil semua user non-admin, tandai jika sudah punya stok
                                    $queryUsers = "
                                        SELECT u.id_user, u.username,
                                            (SELECT COUNT(*) FROM stok_ternak s WHERE s.id_user = u.id_user) AS has_stok
                                        FROM users u
                                        WHERE u.role != 'admin'
                                        ORDER BY u.username ASC
                                    ";
                                    $resultUsers = mysqli_query($conn, $queryUsers);
                                    while($user = mysqli_fetch_assoc($resultUsers)){
                                        $has_stok_attr = $user['has_stok'] > 0 ? 'data-has-stok="1"' : 'data-has-stok="0"';
                                        echo '<option value="'.$user['id_user'].'" '.$has_stok_attr.'>'.htmlspecialchars($user['username']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="form-group mt-3">
                                <label for="idJenisSelect">Jenis Ternak</label>
                                <select name="id_jenis" id="idJenisSelect" class="form-control select2-ternak" required>
                                    <option value="">Pilih Jenis Ternak</option>
                                    <?php
                                    $query = "SELECT id, nama_ternak FROM jenis_ternak ORDER BY nama_ternak ASC";
                                    $resultJenis = mysqli_query($conn, $query);
                                    while($r = mysqli_fetch_assoc($resultJenis)) {
                                        echo '<option value="'.$r['id'].'">'.htmlspecialchars($r['nama_ternak']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- TAMBAHKAN DROPDOWN KATEGORI DI SINI -->
                            <div class="form-group mt-3">
                                <label for="idKategoriSelect">Kategori</label>
                                <select name="id_kategori" id="idKategoriSelect" class="form-control select2-kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    $qK = "SELECT id, nama_kategori FROM kategori_ternak ORDER BY nama_kategori ASC";
                                    $resK = mysqli_query($conn, $qK);
                                    while($k = mysqli_fetch_assoc($resK)) {
                                        echo '<option value="'.$k['id'].'">'.htmlspecialchars($k['nama_kategori']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mt-3">
                                <label for="jumlahJantanInput">Jantan</label>
                                <input type="number" name="jumlah_jantan" id="jumlahJantan" class="form-control" min="0" value="0" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="jumlahBetinaInput">Betina</label>
                                <input type="number" name="jumlah_betina" id="jumlahBetina" class="form-control" min="0" value="0" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="jumlahTotalInput">Jumlah Total</label>
                                <input type="number" id="jumlahTotal" class="form-control" readonly>
                            </div>

                            <div class="form-group mt-3">
                                <label for="keteranganTextarea">Keterangan</label>
                                <textarea name="keterangan" id="keteranganTextarea" class="form-control" placeholder="Tuliskan detail atau catatan terkait ternak"></textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="tanggalInput">Tanggal</label>
                                <input type="datetime-local" name="tanggal" id="tanggalInput" class="form-control" required>
                            </div>
                        </div>
                        
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/admin.js"></script>
    </body>
</html>
