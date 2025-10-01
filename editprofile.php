<?php
include 'cek_login.php';
include 'koneksi.php';

$current_username = $_SESSION['username'];
$success = '';
$error = '';

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
    <title>Edit Profil</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 450px;
            margin: 80px auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #5c9ded;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #43a047;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }

        .alert.success {
            background-color: #e7f9ee;
            color: #2e7d32;
            border: 1px solid #b2dfdb;
        }

        .alert.error {
            background-color: #fdecea;
            color: #c62828;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #555;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Profil</h2>

    <?php if ($success): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username Baru:</label>
        <input type="text" name="new_username" value="<?php echo htmlspecialchars($current_username); ?>" required>

        <label>Password Baru:</label>
        <input type="password" name="new_password" required>

        <label>Konfirmasi Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a class="back-link" href="profile.php">← Kembali ke Profil</a>
</div>
</body>
</html>
