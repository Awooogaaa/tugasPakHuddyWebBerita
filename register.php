<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Cek apakah username sudah ada
    $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO user (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['username'] = $username;
            header("Location: profile.php");
            exit();
        } else {
            $error = "Gagal mendaftar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Berita</title>
    <!-- Added modern styling consistent with login page -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .register-container {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 2rem;
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 450px;
            position: relative;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }

        

        .register-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .register-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: var(--shadow-lg);
        }

        .register-icon i {
            font-size: 2rem;
            color: white;
        }

        .register-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: var(--text-light);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-white);
            color: var(--text-dark);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 1rem;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .error {
            background: #fef2f2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
            font-weight: 500;
        }

        .login-link {
            text-align: center;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--secondary-color);
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .register-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-header">
        <div class="register-icon">
            <i class="bi bi-person-plus"></i>
        </div>
        <h2 class="register-title">Buat Akun Baru</h2>
        <p class="register-subtitle">Daftar untuk bergabung dengan Portal Berita</p>
    </div>
    
    <?php if (isset($error)) echo "<div class='error'><i class='bi bi-exclamation-triangle me-2'></i>$error</div>"; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div style="position: relative;">
                <i class="bi bi-person input-icon"></i>
                <input type="text" name="username" id="username" class="form-input" required placeholder="Pilih username unik">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div style="position: relative;">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="password" class="form-input" required placeholder="Buat password yang kuat">
            </div>
        </div>

        <button type="submit" class="register-btn">
            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
        </button>
        
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </form>
</div>

</body>
</html>
