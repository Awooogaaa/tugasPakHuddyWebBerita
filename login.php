<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['username'])) {
    header("Location: tambahberita.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: tambahberita.php");
            exit();
        }
    }

    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal Berita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007BFF;
            --background-start: #1a2980;
            --background-end: #26d0ce;
            --form-bg: rgba(255, 255, 255, 0.1);
            --text-color: #ffffff;
            --input-bg: rgba(255, 255, 255, 0.2);
            --input-border: rgba(255, 255, 255, 0.5);
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--background-start), var(--background-end));
            background-size: 200% 200%;
            animation: gradientAnimation 10s ease infinite;
            color: var(--text-color);
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background: var(--form-bg);
            padding: 2.5rem 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); /* For Safari */
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1s ease-out;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 1rem;
            opacity: 0.8;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            opacity: 0.7;
            pointer-events: none; /* Mencegah ikon menghalangi klik pada input */
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.5rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 0.75rem;
            color: var(--text-color);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            border-color: #ffffff;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(-1px);
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            color: #ffcccc;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h2 class="login-title">Selamat Datang</h2>
        <p class="login-subtitle">Masuk untuk mengakses dasbor Anda</p>
    </div>
    
    <?php if (isset($error)) : ?>
        <div class='error'>
            <i class='bi bi-exclamation-triangle-fill'></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <i class="bi bi-person-fill input-icon"></i>
            <input type="text" name="username" class="form-input" required placeholder="Username">
        </div>

        <div class="form-group">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" name="password" class="form-input" required placeholder="Password">
        </div>

        <button type="submit" class="login-btn">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk</span>
        </button>
    </form>
</div>

</body>
</html>