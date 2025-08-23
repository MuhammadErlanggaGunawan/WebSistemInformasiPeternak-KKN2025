<?php
// Pastikan session dimulai jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">
      <nav class="navbar navbar-expand-lg opacity-8 blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 p-2 start-0 end-0 mx-4">
        <div class="container-fluid px-0">
          <a class="navbar-brand font-weight-bolder ms-sm-3 text-sm" href="index.php">
            <img src="./assets/img/log.png" height="40px" alt="SITernak Logo">
          </a>
          <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2"><span class="navbar-toggler-bar bar1"></span><span class="navbar-toggler-bar bar2"></span><span class="navbar-toggler-bar bar3"></span></span>
          </button>
          <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ms-auto">
              <li class="nav-item mx-2">
                <!-- PERBAIKAN: Perbesar ikon & kurangi jarak -->
                <a href="index.php" class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= ($currentPage === 'beranda') ? 'font-weight-bold text-success' : 'font-weight-semibold' ?>">
                  <i class="material-symbols-rounded opacity-6 me-1 text-lg">home</i> Beranda
                </a>
              </li>
              <li class="nav-item mx-2">
                <!-- PERBAIKAN: Perbesar ikon & kurangi jarak -->
                <a href="tentang-kami.php" class="nav-link ps-2 d-flex cursor-pointer align-items-center <?= ($currentPage === 'tentang') ? 'font-weight-bold text-success' : 'font-weight-semibold' ?>">
                  <i class="material-symbols-rounded opacity-6 me-1 text-lg">info</i> Tentang Kami
                </a>
              </li>
              <?php if (isset($_SESSION['log']) && $_SESSION['log'] === true): ?>
              <li class="nav-item dropdown">
                <!-- PERBAIKAN: Perbesar ikon & kurangi jarak -->
                <a class="nav-link dropdown-toggle ps-2 d-flex cursor-pointer align-items-center font-weight-semibold" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="material-symbols-rounded opacity-6 me-1 text-lg">account_circle</i> <?= htmlspecialchars($_SESSION['username']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li><a class="dropdown-item" href="<?= $_SESSION['role'] === 'admin' ? 'admin.php' : 'profil.php' ?>"><?= $_SESSION['role'] === 'admin' ? 'Dashboard Admin' : 'Profil Saya' ?></a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
              </li>
              <?php else: ?>
              <li class="nav-item my-auto ms-3 ms-lg-0">
                <a href="login.php" class="btn bg-gradient-dark mb-0">Login</a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </div>
</div>