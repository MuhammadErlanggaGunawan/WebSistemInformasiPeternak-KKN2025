<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="./assets/img/log.png">
  <title>Tentang Kami - SITernak Cimande</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
  <!-- Nucleo Icons -->
  <link href="css/nucleo-icons.css" rel="stylesheet">
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <!-- PERBAIKAN: Tambahkan Font Awesome untuk ikon sosial media -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- CSS Files -->
  <link id="pagestyle" href="css/material-kit.css?v=3.1.0" rel="stylesheet">
</head>

<body class="about-us-page bg-gray-200">

  <!-- Navbar -->
  <?php 
    $currentPage = 'tentang'; // Set halaman aktif
    require 'templates/navbar.php'; 
  ?>

  <!-- Header -->
  <header class="header-2">
    <div class="page-header min-vh-50" style="background-image: url('./assets/img/bg-about.jpg');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mx-auto my-auto">
            <h1 class="text-white">Tentang Sistem Informasi Ternak (SITernak)</h1>
            <p class="lead mb-4 text-white opacity-8">Sebuah platform digital untuk memodernisasi manajemen data peternakan di Desa Cimande.</p>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <section class="py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h3 class="font-weight-bolder mb-4">Apa itu SITernak?</h3>
            <p>
              SITernak adalah sebuah sistem informasi berbasis web yang dirancang khusus untuk mendata dan memantau populasi hewan ternak di Desa Cimande. Sistem ini merupakan hasil kolaborasi mahasiswa KKN dari Universitas Djuanda (FILKOM & FAPERTA) sebagai bentuk pengabdian kepada masyarakat.
            </p>
            <p>
              Tujuan utama dari platform ini adalah untuk menyediakan data yang akurat, terpusat, dan mudah diakses bagi para peternak dan pihak pengelola desa.
            </p>
          </div>
          <div class="col-lg-4 ms-auto mt-lg-0 mt-4">
            <div class="card shadow-lg">
              <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                <img src="./assets/img/bg21.png" alt="img-blur-shadow" class="img-fluid border-radius-lg">
              </div>
              <div class="card-body">
                <h5 class="font-weight-bolder">Manajemen Data Modern</h5>
                <p>Meninggalkan cara manual dan beralih ke sistem digital yang efisien dan akurat.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5 pt-5">
          <div class="col-lg-8 mx-auto text-center">
            <h3 class="font-weight-bolder">Manfaat Utama Sistem</h3>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-4">
            <div class="info text-center">
              <div class="icon icon-shape icon-lg bg-gradient-success shadow-success mx-auto">
                <i class="material-symbols-rounded opacity-10">monitoring</i>
              </div>
              <h5 class="mt-3">Pemantauan Real-time</h5>
              <p>Admin dapat melihat total populasi, jumlah jantan/betina, dan data per peternak secara langsung.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info text-center">
              <div class="icon icon-shape icon-lg bg-gradient-info shadow-info mx-auto">
                <i class="material-symbols-rounded opacity-10">groups</i>
              </div>
              <h5 class="mt-3">Data Terpusat</h5>
              <p>Semua informasi peternak dan ternaknya tersimpan aman dalam satu database, mengurangi risiko data hilang.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info text-center">
              <div class="icon icon-shape icon-lg bg-gradient-warning shadow-warning mx-auto">
                <i class="material-symbols-rounded opacity-10">analytics</i>
              </div>
              <h5 class="mt-3">Dasar Pengambilan Keputusan</h5>
              <p>Data yang akurat dapat digunakan untuk perencanaan program bantuan atau pengembangan peternakan desa.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- PERBAIKAN: FOOTER BARU YANG LEBIH MENARIK -->
  <footer class="footer mt-5 pt-5 pb-4 bg-gray-900 text-white">
    <div class="container">
      <div class="row">
        <!-- Kolom 1: Tentang -->
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
          <img src="./assets/img/log.png" height="50px" alt="SITernak Logo" class="mb-3">
          <h6 class="text-white">Sistem Informasi Ternak Cimande</h6>
          <p class="text-sm opacity-8">Platform digital untuk pendataan dan visualisasi populasi hewan ternak di Desa Cimande, hasil kolaborasi KKN Mahasiswa.</p>
        </div>
        <!-- Kolom 2: Tautan Cepat -->
        <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
          <h6 class="text-white">Tautan Cepat</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="index.php" class="text-sm text-white opacity-8">Beranda</a></li>
            <li class="mb-2"><a href="tentang-kami.php" class="text-sm text-white opacity-8">Tentang Kami</a></li>
            <?php if (isset($_SESSION['log']) && $_SESSION['log'] === true): ?>
              <li class="mb-2"><a href="<?= $_SESSION['role'] === 'admin' ? 'admin.php' : 'profil.php' ?>" class="text-sm text-white opacity-8">Dashboard</a></li>
            <?php else: ?>
              <li class="mb-2"><a href="login.php" class="text-sm text-white opacity-8">Login</a></li>
            <?php endif; ?>
          </ul>
        </div>
        <!-- Kolom 3: Hubungi Kami -->
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
          <h6 class="text-white">Hubungi Kami</h6>
          <div class="d-flex align-items-start mb-2">
            <i class="material-symbols-rounded opacity-8 me-2 mt-1">place</i>
            <span class="text-sm opacity-8">Kantor Desa Cimande, Kec. Caringin, <br>Kabupaten Bogor, Jawa Barat 16730</span>
          </div>
          <div class="d-flex align-items-center mb-2">
            <i class="material-symbols-rounded opacity-8 me-2">mail</i>
            <span class="text-sm opacity-8">kontak@cimande.desa.id</span>
          </div>
          <div class="d-flex align-items-center mb-2">
            <i class="material-symbols-rounded opacity-8 me-2">phone</i>
            <span class="text-sm opacity-8">(0251) 123-4567</span>
          </div>
        </div>
        <!-- Kolom 4: Sosial Media (opsional, bisa diisi nanti) -->
        <div class="col-lg-2 col-md-6">
           <h6 class="text-white">Ikuti Kami</h6>
           <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg opacity-8"></i></a>
           <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg opacity-8"></i></a>
           <a href="#" class="text-white"><i class="fab fa-youtube fa-lg opacity-8"></i></a>
        </div>
      </div>
      <hr class="horizontal light my-4">
      <div class="row">
        <div class="col-12 text-center">
          <p class="text-sm text-white opacity-8 mb-0">
            All rights reserved. Copyright © <script>document.write(new Date().getFullYear())</script> SITernak by KKN Cimande FILKOM x Faperta.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!--   Core JS Files   -->
  <script src="js/core/popper.min.js" type="text/javascript"></script>
  <script src="js/core/bootstrap.min.js" type="text/javascript"></script>
  <script src="js/plugins/perfect-scrollbar.min.js"></script>
  <script src="js/material-kit.min.js?v=3.1.0" type="text/javascript"></script>
</body>
</html>