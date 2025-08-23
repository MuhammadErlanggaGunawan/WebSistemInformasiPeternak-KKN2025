<?php
require 'function.php';
// Tidak perlu cek login di sini agar halaman bisa diakses publik
// require 'ceklogin.php';
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="./assets/img/log.png">
  <title>SITernak Cimande</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
  <link href="css/nucleo-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <!-- PERBAIKAN: Tambahkan Font Awesome untuk ikon sosial media -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link id="pagestyle" href="css/material-kit.css?v=3.1.0" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="index-page bg-gray-200">

  <!-- Navbar -->
  <?php 
    $currentPage = 'beranda'; // Set halaman aktif
    require 'templates/navbar.php'; 
  ?>

  <header class="header-2">
    <div class="page-header min-vh-75 relative" style="background-image: url('./assets/img/bg21.png')">
      <span class="mask bg-gradient-dark opacity-4"></span>
      <div class="container"><div class="row"><div class="col-lg-7 text-center mx-auto"><img src="./assets/img/log.png" height="100px" alt="SITernak Logo" class="mb-3"><h1 class="text-white font-weight-black">Sistem Informasi Ternak</h1><p class="lead text-white mt-3">Data Populasi Hewan Ternak<br>Di Desa Cimande, Bogor, Jawa Barat</p></div></div></div>
    </div>
  </header>

  <!-- Card Utama untuk Statistik dan Grafik -->
  <div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
    <!-- ... (Section Statistik) ... -->
    <section class="pt-3 pb-4" id="count-stats">
      <div class="container">
        <h5 class="mt-3" style="text-align: center;">Total Populasi Hewan Ternak Utama</h5>
        <div class="row">
          <div class="col-lg-12 mx-auto py-3">
            <div class="row">
              <div class="col-md-3 col-6 mb-4 position-relative"><div class="p-3 text-center"><h1 class="text-gradient text-dark"><span id="total-sapi">-</span></h1><h5 class="mt-3">Jumlah Sapi</h5><p class="text-sm font-weight-normal">Betina: <span id="sapi-betina">-</span> | Jantan: <span id="sapi-jantan">-</span></p></div><hr class="vertical dark d-md-block d-none"></div>
              <div class="col-md-3 col-6 mb-4 position-relative"><div class="p-3 text-center"><h1 class="text-gradient text-dark"><span id="total-kambing">-</span></h1><h5 class="mt-3">Jumlah Kambing</h5><p class="text-sm font-weight-normal">Betina: <span id="kambing-betina">-</span> | Jantan: <span id="kambing-jantan">-</span></p></div><hr class="vertical dark d-md-block d-none"></div>
              <div class="col-md-3 col-6 mb-4 position-relative"><div class="p-3 text-center"><h1 class="text-gradient text-dark"><span id="total-domba">-</span></h1><h5 class="mt-3">Jumlah Domba</h5><p class="text-sm font-weight-normal">Betina: <span id="domba-betina">-</span> | Jantan: <span id="domba-jantan">-</span></p></div><hr class="vertical dark d-md-block d-none"></div>
              <div class="col-md-3 col-6 mb-4"><div class="p-3 text-center"><h1 class="text-gradient text-dark"><span id="total-lainnya">-</span></h1><h5 class="mt-3">Lainnya</h5><p class="text-sm font-weight-normal">Betina: <span id="lainnya-betina">-</span> | Jantan: <span id="lainnya-jantan">-</span></p></div></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ... (Section Grafik) ... -->
    <section class="pb-5 pt-2 border-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0"><h5 class="mb-4 text-center">Komposisi Populasi</h5><div style="height: 350px; position: relative;"><canvas id="populasiChart"></canvas></div></div>
                <div class="col-lg-6"><h5 class="mb-4 text-center">Rincian Jantan vs Betina</h5><div style="height: 350px; position: relative;"><canvas id="genderChart"></canvas></div></div>
            </div>
        </div>
    </section>
  </div>

  <!-- ... (Section Daftar Peternak) ... -->
  <div class="container my-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <section class="py-4">
          <div class="container">
            <div class="row"><div class="col-lg-12"><h5 class="mb-4" style="text-align: center;">Daftar Peternak dan Stok Ternak</h5></div></div>
            <div class="row mb-4"><div class="col-lg-6 col-md-8 mx-auto"><div class="input-group input-group-outline"><label class="form-label">Cari nama peternak...</label><input type="text" id="searchInput" class="form-control"></div></div></div>
            <div class="row" id="peternak-card-container" style="min-height: 200px;"><div class="col-12 text-center p-4"><p>Memuat data peternak...</p></div></div>
            <div class="row mt-4"><div class="col-12 d-flex justify-content-center"><nav id="pagination-container"></nav></div></div>
          </div>
        </section>
      </div>
    </div>
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

  <script src="js/core/popper.min.js"></script>
  <script src="js/core/bootstrap.min.js"></script>
  <script src="js/plugins/perfect-scrollbar.min.js"></script>
  <script src="js/material-kit.min.js?v=3.1.0"></script>
  <script>
    // ... (Seluruh kode JavaScript tetap sama) ...
    function updateText(id, value) { document.getElementById(id).textContent = value; }
    fetch('api/stok-public.php')
      .then(response => response.json())
      .then(data => {
        updateText('total-sapi', data.sapi.total); updateText('sapi-betina', data.sapi.total_betina); updateText('sapi-jantan', data.sapi.total_jantan);
        updateText('total-kambing', data.kambing.total); updateText('kambing-betina', data.kambing.total_betina); updateText('kambing-jantan', data.kambing.total_jantan);
        updateText('total-domba', data.domba.total); updateText('domba-betina', data.domba.total_betina); updateText('domba-jantan', data.domba.total_jantan);
        updateText('total-lainnya', data.lainnya.total); updateText('lainnya-betina', data.lainnya.total_betina); updateText('lainnya-jantan', data.lainnya.total_jantan);
        // 1. Render Pie Chart (Komposisi)
        const pieCtx = document.getElementById('populasiChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Sapi', 'Kambing', 'Domba', 'Lainnya'],
                datasets: [{
                    label: 'Jumlah Populasi',
                    // PERBAIKAN: Tukar warna hijau (#4CAF50) dan biru (#03A9F4)
                    data: [data.sapi.total, data.kambing.total, data.domba.total, data.lainnya.total],
                    backgroundColor: ['#E91E63', '#03A9F4', '#4CAF50', '#607D8B'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
        const barCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(barCtx, { type: 'bar', data: { labels: ['Sapi', 'Kambing', 'Domba', 'Lainnya'], datasets: [ { label: 'Betina', data: [data.sapi.total_betina, data.kambing.total_betina, data.domba.total_betina, data.lainnya.total_betina], backgroundColor: 'rgba(233, 30, 99, 0.7)', borderColor: '#E91E63', borderWidth: 1 }, { label: 'Jantan', data: [data.sapi.total_jantan, data.kambing.total_jantan, data.domba.total_jantan, data.lainnya.total_jantan], backgroundColor: 'rgba(3, 169, 244, 0.7)', borderColor: '#03A9F4', borderWidth: 1 } ] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { position: 'top' } } } });
      })
      .catch(error => console.error('Error fetching public stats:', error));
    const cardContainer = document.getElementById('peternak-card-container');
    const paginationContainer = document.getElementById('pagination-container');
    const searchInput = document.getElementById('searchInput');
    function renderPagination(pagination) { paginationContainer.innerHTML = ''; if (pagination.total_pages <= 1) return; let paginationHTML = '<ul class="pagination pagination-dark">'; paginationHTML += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">‹</a></li>`; for (let i = 1; i <= pagination.total_pages; i++) { paginationHTML += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; } paginationHTML += `<li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">›</a></li>`; paginationHTML += '</ul>'; paginationContainer.innerHTML = paginationHTML; }
    function fetchPeternak(page = 1, searchTerm = '') {
        cardContainer.innerHTML = '<div class="col-12 text-center p-4"><p>Memuat data peternak...</p></div>';
        fetch(`api/peternak-list.php?page=${page}&search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(response => {
                cardContainer.innerHTML = '';
                const peternakData = response.data;
                const paginationData = response.pagination;

                if (peternakData.length === 0) {
                    cardContainer.innerHTML = `<div class="col-12 text-center p-4"><p>Data peternak tidak ditemukan.</p></div>`;
                    paginationContainer.innerHTML = '';
                    return;
                }

                peternakData.forEach(peternak => {
                    const noHpDisplay = peternak.no_hp ? peternak.no_hp : '-';
                    const alamatDisplay = peternak.alamat ? peternak.alamat : '-';

                    // Render badge ternak
                    let ternakBadges = '';
                    let ternakList = '';
                    let kategoriBadge = '';

                    peternak.ternak.forEach((t, index) => {
                        function getBadgeColor(jenis) {
                            if (!jenis) return 'bg-gradient-dark';
                            const j = jenis.toLowerCase();
                            if (j.includes('sapi')) return 'bg-gradient-danger';
                            if (j.includes('kambing')) return 'bg-gradient-info';
                            if (j.includes('domba')) return 'bg-gradient-success';
                            return 'bg-gradient-dark';
                        }
                        const badgeColor = getBadgeColor(t.jenis);

                        // Ambil kategori dari data pertama aja
                        if (index === 0 && t.kategori) {
                            kategoriBadge = `<span class="badge bg-gradient-secondary ms-2 px-2 py-1">${t.kategori}</span>`;
                        }

                        // Badge jenis ternak
                        ternakBadges += `<span class="badge ${badgeColor} me-1 px-2 py-1">${t.jenis}</span>`;

                        ternakList += `
                          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                              <div class="fw-bold text-capitalize">${t.jenis}</div>
                              <div class="d-flex gap-3 mt-1">
                                <span class="text-muted">Betina: <span class="fw-bold text-dark">${t.betina}</span></span>
                                <span class="text-muted">Jantan: <span class="fw-bold text-dark">${t.jantan}</span></span>
                                <span class="text-muted">Total: <span class="fw-bold text-dark">${t.total}</span></span>
                              </div>
                            </div>
                          </li>`;

                    });

                    cardContainer.innerHTML += `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <!-- Nama + kategori -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon icon-shape icon-sm bg-gradient-success shadow-success text-center me-3">
                                            <i class="material-symbols-rounded opacity-10">person</i>
                                        </div>
                                        <h5 class="mb-0 font-weight-bolder">
                                            ${peternak.nama_peternak} ${kategoriBadge}
                                        </h5>
                                    </div>

                                    <!-- Badge jenis -->
                                    <div class="mb-3">
                                        ${ternakBadges}
                                    </div>

                                    <!-- List ternak -->
                                    <ul class="list-group list-group-flush mb-3">
                                        ${ternakList}
                                    </ul>

                                    <!-- Kontak -->
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="material-symbols-rounded align-middle me-2 text-muted" style="font-size: 18px;">call</i>
                                        <span class="font-weight-bold">${noHpDisplay}</span>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded align-middle me-2 text-muted" style="font-size: 18px;">home</i>
                                        <span class="font-weight-bold">${alamatDisplay}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });



                renderPagination(paginationData);
            })
            .catch(error => {
                console.error('Error fetching peternak list:', error);
                cardContainer.innerHTML = '<div class="col-12 text-center p-4 text-danger"><p>Gagal memuat data peternak.</p></div>';
            });
    }

    paginationContainer.addEventListener('click', function(e) {
      e.preventDefault();
      if (e.target.tagName === 'A' && e.target.dataset.page) {
        const page = parseInt(e.target.dataset.page);
        if (!isNaN(page)) {
          fetchPeternak(page, searchInput.value);
        }
      }
    });

    let searchTimeout;
    searchInput.addEventListener('keyup', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchPeternak(1, searchInput.value);
      }, 300);
    });

    fetchPeternak(1);
  </script>
</body>
</html>