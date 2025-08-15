<?php
require 'function.php';
require 'ceklogin.php';

// 🔹 Set variabel $success dan $error
$success = '';
$error = '';

// 🔹 Cek parameter URL
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'cleared') {
        $success = 'Semua riwayat berhasil dihapus.';
    }
}


$query = "
    SELECT 
      r.id_riwayat, 
      r.aksi, 
      r.tanggal_update, 
      u.nama_lengkap AS user_nama, 
      u.username     AS user_username,
      a.username     AS admin_username,
      a.nama_lengkap AS admin_nama,
      r.keterangan
    FROM riwayat_stok r
    LEFT JOIN users u ON r.id_user = u.id_user       -- user yang stoknya berubah
    LEFT JOIN users a ON r.admin_id = a.id_user      -- admin yang melakukan aksi
    ORDER BY r.tanggal_update DESC
";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Riwayat Perubahan</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
        /* Smooth transition untuk body */
        body {
            opacity: 1;
            transition: opacity 0.4s ease;
            margin: 0;
        }

        /* Kelas untuk efek fade out */
        .fade-out {
            opacity: 0;
            pointer-events: none;
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
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'wrong_password'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Password salah! Hapus riwayat dibatalkan.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php elseif ($_GET['error'] == 'invalid_request'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Permintaan tidak valid.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php elseif ($_GET['error'] == 'delete_failed'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal menghapus riwayat. Coba lagi.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <!-- notification area -->
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
                        <h1 class="mt-4 mb-4">Riwayat Perubahan</h1>
                        <div class="card mb-4">
                            <button type="button" class="btn btn-danger btn-sm" onclick="konfirmasiHapusDenganPassword()">
                                <i class="fas fa-trash"></i> Hapus Semua Riwayat
                            </button>
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Admin</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars((string)($row['user_nama'] ?? $row['user_username'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string)($row['admin_username'] ?? $row['admin_nama'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string)($row['keterangan'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string)($row['tanggal_update'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars((string)($row['aksi'] ?? '')) ?></td>
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="js/scripts.js"></script>
        <script>
        function konfirmasiHapusDenganPassword() {
            const password = prompt('Masukkan password Anda untuk konfirmasi:\n\n(Hanya admin yang boleh hapus semua riwayat)');

            if (!password) return;

            // Kirim data via fetch (POST)
            fetch('hapus_riwayat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'password=' + encodeURIComponent(password)
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'riwayat.php?status=cleared';
                } else {
                    window.location.href = 'riwayat.php?error=wrong_password';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = 'riwayat.php?error=invalid_request';
            });
        }
        </script>
    </body>
</html>
