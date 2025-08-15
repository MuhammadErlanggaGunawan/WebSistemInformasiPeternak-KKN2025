<?php
require 'function.php';
require 'ceklogin.php';

$role = $_SESSION['role'] ?? null;
if ($role != 'admin') {
    $_SESSION['error'] = "Akses ditolak! Halaman ini hanya untuk admin.";
    header('Location: index.php');
    exit;
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Jika ada query ?id=... maka ambil data user untuk edit
$isEdit = false;
$editId = 0;
$editUser = [
    'nama_lengkap' => '',
    'email' => '',
    'alamat' => '',
    'no_hp' => '',
    'role' => 'peternak'
];

if (!empty($_GET['id'])) {
    $editId = intval($_GET['id']);
    if ($editId > 0) {
        $stmt = $conn->prepare("SELECT id_user, nama_lengkap, email, alamat, no_hp, role FROM users WHERE id_user = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $editId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $isEdit = true;
                $editUser['nama_lengkap'] = $row['nama_lengkap'];
                $editUser['email'] = $row['email'];
                $editUser['alamat'] = $row['alamat'];
                $editUser['no_hp'] = $row['no_hp'];
                $editUser['role'] = $row['role'];
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $isEdit ? 'Edit User' : 'Tambah User' ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }
        </style>
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white text-center">
                            <h4 class="mb-0"><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <?= htmlspecialchars($success) ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php elseif (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <?= htmlspecialchars($error) ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="proses_user.php" novalidate>
                                <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'insert' ?>">
                                <?php if ($isEdit): ?>
                                    <input type="hidden" name="id_user" value="<?= $editId ?>">
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="namaLengkap">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="namaLengkap" class="form-control" placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($editUser['nama_lengkap'] ?: ($_POST['nama_lengkap'] ?? '')) ?>" required minlength="3">
                                </div>

                                <div class="form-group">
                                    <label for="emailUser">Email</label>
                                    <input type="email" name="email" id="emailUser" class="form-control" placeholder="contoh: nama@gmail.com" value="<?= htmlspecialchars($editUser['email'] ?: ($_POST['email'] ?? '')) ?>" required>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="passwordUser">Password <?= $isEdit ? '<small class="text-muted">(kosongkan jika tidak ingin mengubah)</small>' : '' ?></label>
                                    <input type="password" name="password" id="passwordUser" class="form-control" <?= $isEdit ? '' : 'required minlength="8"' ?> placeholder="<?= $isEdit ? 'Biarkan kosong untuk tidak mengubah password' : 'Minimal 8 karakter' ?>">
                                    <button type="button" class="btn btn-sm position-absolute" style="top: 35px; right: 10px;" onclick="togglePassword('passwordUser')">
                                        <i class="fas fa-eye" id="togglePasswordIcon_passwordUser"></i>
                                    </button>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="passwordConfirmUser">Konfirmasi Password <?= $isEdit ? '<small class="text-muted">(kosongkan jika tidak ingin mengubah)</small>' : '' ?></label>
                                    <input type="password" name="password_confirm" id="passwordConfirmUser" class="form-control" <?= $isEdit ? '' : 'required minlength="8"' ?> placeholder="<?= $isEdit ? 'Ulangi password jika mengubah' : 'Ulangi password' ?>">
                                    <button type="button" class="btn btn-sm position-absolute" style="top: 35px; right: 10px;" onclick="togglePassword('passwordConfirmUser')">
                                        <i class="fas fa-eye" id="togglePasswordIcon_passwordConfirmUser"></i>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label for="noHpUser">No HP</label>
                                    <input type="text" name="no_hp" id="noHpUser" class="form-control" pattern="\d{10,13}" title="Nomor HP harus 10-13 digit angka" required placeholder="08123456789" value="<?= htmlspecialchars($editUser['no_hp'] ?: ($_POST['no_hp'] ?? '')) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="roleUser">Role</label>
                                    <select name="role" id="roleUser" class="form-control select2-role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" <?= ($editUser['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                        <option value="peternak" <?= ($editUser['role'] === 'peternak') ? 'selected' : '' ?>>Peternak</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="alamatUser">Alamat</label>
                                    <textarea name="alamat" id="alamatUser" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap" minlength="5"><?= htmlspecialchars($editUser['alamat'] ?: ($_POST['alamat'] ?? '')) ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between" style="gap:10px;">
                                    <a href="user.php" class="btn btn-dark w-50" role="button">Batal</a>
                                    <button type="submit" class="btn btn-primary w-50"><?= $isEdit ? 'Update User' : 'Tambah User' ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/tambah_user.js"></script>
        <script>
        // pastikan select2 men-set value ketika halaman load (untuk mode edit)
        $(function(){ $('.select2-role').val('<?= htmlspecialchars($editUser['role']) ?>').trigger('change'); });
        </script>
    </body>
</body>
</html>
