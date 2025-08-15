<?php
require 'function.php'; // session_start() di function.php

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ambil user berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // sukses login
            $_SESSION['log'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // redirect sesuai role
            header('Location: ' . ($user['role'] === 'admin' ? 'admin.php' : 'index.php'));
            exit;
        } else {
            // password salah
            header('Location: login.php?error=1');
            exit;
        }
    } else {
        // email tidak ditemukan
        header('Location: login.php?error=1');
        exit;
    }
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
        <title>Login</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header bg-dark text-white text-center">
                                <h3 class="font-weight-light my-2">Login</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        Email atau password salah!
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <form method="post">
                                    <div class="form-group">
                                        <label for="inputEmailAddress">Email</label>
                                        <input class="form-control" name="email" id="inputEmailAddress" type="email" placeholder="Masukkan email" required>
                                    </div>
                                    <div class="form-group position-relative">
                                        <label for="inputPassword">Password</label>
                                        <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Masukkan password" required>
                                        <button type="button" class="btn btn-sm position-absolute" 
                                                style="top: 35px; right: 10px;" 
                                                onclick="togglePassword('inputPassword')">
                                            <i class="fas fa-eye" id="togglePasswordIcon_inputPassword"></i>
                                        </button>
                                    </div>
                                    <div class="form-group d-flex justify-content-between mt-4 mb-0">
                                        <button class="btn btn-primary btn-block" name="login">Login</button>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <small class="text-muted">
                                            Belum punya akun? Silahkan hubungi admin.
                                        </small>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('togglePasswordIcon_' + id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
