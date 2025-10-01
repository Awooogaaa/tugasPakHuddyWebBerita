<?php
include 'cek_login.php';
include 'koneksi.php';
include 'sidebar.php';

$current_username = $_SESSION['username'];
$success = '';
$error = '';

// Jika tombol simpan ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = htmlspecialchars(trim($_POST['new_username']));
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        if ($new_username !== $current_username) {
            $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$new_username'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Username sudah digunakan!";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = mysqli_query($conn, "UPDATE user SET username='$new_username', password='$hashed_password' WHERE username='$current_username'");
                if ($update) {
                    $_SESSION['username'] = $new_username;
                    $success = "Username dan password berhasil diubah!";
                    $current_username = $new_username;
                } else {
                    $error = "Terjadi kesalahan saat menyimpan perubahan.";
                }
            }
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE user SET password='$hashed_password' WHERE username='$current_username'");
            if ($update) {
                $success = "Password berhasil diubah!";
            } else {
                $error = "Terjadi kesalahan saat mengubah password.";
            }
        }
    } else {
        $error = "Konfirmasi password tidak cocok!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid p-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      
      <!-- Notifikasi -->
      <?php if ($success): ?>
        <div class="alert alert-success text-center"><?= $success; ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger text-center"><?= $error; ?></div>
      <?php endif; ?>

      <!-- Card Profil -->
      <div class="card shadow-lg mb-4">
        <div class="card-body text-center">
          <i class="bi bi-person-circle mb-3" style="font-size: 5rem; color:#0d6efd;"></i>
          <h4 class="card-title">Profil Siswa</h4>
          <p class="card-text"><strong>Username:</strong> <?= $current_username; ?></p>
          <button class="btn btn-primary mt-2" data-bs-toggle="collapse" data-bs-target="#editForm">
            <i class="bi bi-pencil-square"></i> Edit Profil
          </button>
        </div>
      </div>

      <!-- Form Edit (collapse) -->
      <div class="collapse" id="editForm">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="mb-3">Edit Profil</h5>
            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Username Baru:</label>
                <input type="text" class="form-control" name="new_username" value="<?= htmlspecialchars($current_username); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password Baru:</label>
                <input type="password" class="form-control" name="new_password" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Konfirmasi Password:</label>
                <input type="password" class="form-control" name="confirm_password" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
