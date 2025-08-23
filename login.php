<?php
require 'function.php';

// Cek jika sudah login, redirect ke halaman sesuai role
if (isset($_SESSION['log'])) {
    if ($_SESSION['role'] == 'admin') {
        header('location:admin.php');
    } else {
        header('location:index.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="./assets/img/log.png">
  <title>Login - SITernak Cimande</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="css/material-kit.css?v=3.1.0" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="sign-in-basic">
  <div class="page-header align-items-start min-vh-100" style="background-image: url('./assets/img/bg21.png');" loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Login SITernak</h4>
                <p class="text-white text-center mb-2">Masukkan username dan password Anda</p>
              </div>
            </div>
            <div class="card-body">
              <form role="form" class="text-start" method="post">
                
                <?php if(isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger text-white text-sm" role="alert">
                    <?= $_SESSION['login_error']; ?>
                </div>
                <?php unset($_SESSION['login_error']); endif; ?>

                <!-- PERBAIKAN: Ubah label agar lebih jelas -->
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Username atau Email</label>
                  <input type="text" name="username" class="form-control" required>
                </div>
                <div class="input-group input-group-outline mb-3">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required id="password">
                  <span class="input-group-text" 
                        style="cursor: pointer; 
                              background: transparent; 
                              border: none; 
                              position: absolute; 
                              right: 15px; 
                              top: 50%; 
                              transform: translateY(-50%); 
                              pointer-events: auto; 
                              z-index: 10;"
                        onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                  </span>
                </div>
                
                <!-- PERBAIKAN: Tambahkan kembali checkbox "Ingat Saya" -->
                <div class="form-check form-switch d-flex align-items-center mb-3">
                  <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberme">
                  <label class="form-check-label mb-0 ms-3" for="rememberMe">Ingat saya</label>
                </div>

                <div class="text-center">
                  <button type="submit" name="login" class="btn bg-gradient-success w-100 my-4 mb-2">Login</button>
                </div>
                 <p class="text-sm text-center">
                  <a href="index.php" class="text-dark">Kembali ke Beranda</a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="js/core/popper.min.js" type="text/javascript"></script>
  <script src="js/core/bootstrap.min.js" type="text/javascript"></script>
  <script src="js/material-kit.min.js?v=3.1.0" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"></script>
  <script>
  function togglePassword() {
      const password = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (password.type === 'password') {
          password.type = 'text';
          toggleIcon.classList.remove('fa-eye');
          toggleIcon.classList.add('fa-eye-slash');
      } else {
          password.type = 'password';
          toggleIcon.classList.remove('fa-eye-slash');
          toggleIcon.classList.add('fa-eye');
      }
  }
  </script>
</body>
</html>
