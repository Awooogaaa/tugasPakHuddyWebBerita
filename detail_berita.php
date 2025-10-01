<?php
session_start();
include "koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM berita WHERE id_berita = $id");
$berita = mysqli_fetch_assoc($result);

if (!$berita) {
    echo "Berita tidak ditemukan!";
    exit;
}

$beritaLain = mysqli_query($conn, "SELECT id_berita, judul, gambar 
                                      FROM berita 
                                      WHERE id_berita != $id 
                                      ORDER BY id_berita DESC 
                                      LIMIT 5");

$isLogin = isset($_SESSION['username']);

$komentar = mysqli_query($conn, "SELECT * FROM komentar WHERE id_berita = $id ORDER BY tanggal DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_komentar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $isi_komentar = mysqli_real_escape_string($conn, $_POST['isi_komentar']);
    if (!empty($nama) && !empty($isi_komentar)) {
        mysqli_query($conn, "INSERT INTO komentar (id_berita, nama, isi_komentar) 
                                  VALUES ($id, '$nama', '$isi_komentar')");
        header("Location: detail_berita.php?id=" . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($berita['judul']); ?> - Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 25px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .detail-container {
            margin-top: 2.5rem;
            margin-bottom: 2.5rem;
        }

        .detail-card {
            background: var(--bg-white);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(37, 99, 235, 0.1);
            position: relative;
            margin-bottom: 2.5rem;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .detail-image {
            width: 100%;
            height: 450px; /* Gambar dibuat lebih tinggi untuk tampilan lebih dramatis */
            object-fit: cover;
        }

        .detail-content {
            padding: 2.5rem; /* Padding lebih besar */
        }

        .detail-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem; /* Judul lebih besar */
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .detail-text {
            font-size: 1.1rem; /* Ukuran font isi berita lebih nyaman dibaca */
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        .back-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-md);
        }

        .back-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        /* Styling untuk Berita Lainnya & Komentar */
        .extra-section {
            background: var(--bg-white);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(37, 99, 235, 0.1);
            margin-bottom: 2.5rem;
        }

        .extra-section h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Styling item Berita Lainnya */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 0.75rem;
            border: 1px solid transparent;
        }

        .sidebar-item:hover {
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.05);
            border-color: rgba(37, 99, 235, 0.1);
            transform: translateX(4px);
        }

        .sidebar-item img {
            width: 80px; /* Gambar sedikit lebih besar */
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .sidebar-item span {
            font-weight: 500;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        /* Styling Komentar */
        .komentar-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
        }

        .komentar-item:last-child {
            border-bottom: none;
        }

        .komentar-nama {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .komentar-tanggal {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .komentar-isi {
            color: var(--text-dark);
            line-height: 1.6;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 768px) {
            .detail-title { font-size: 1.75rem; }
            .detail-content { padding: 1.5rem; }
            .detail-image { height: 250px; }
            .extra-section { padding: 1.5rem; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="berita.php">
        <i class="bi bi-newspaper me-2"></i>Portal Berita
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if ($isLogin) { ?>
          <li class="nav-item">
              <a class="nav-link" href="profile.php"><i class="bi bi-person-circle me-1"></i>Profile</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
              <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="register.php"><i class="bi bi-person-plus me-1"></i>Register</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container detail-container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
        
            <article class="detail-card">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($berita['gambar']); ?>" 
                     class="detail-image" alt="<?php echo htmlspecialchars($berita['judul']); ?>">
                <div class="detail-content">
                    <h1 class="detail-title"><?php echo htmlspecialchars($berita['judul']); ?></h1>
                    <div class="detail-text"><?php echo nl2br(htmlspecialchars($berita['isi'])); ?></div>
                    <a href="berita.php" class="back-btn">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </article>

            <div class="extra-section">
                <h4><i class="bi bi-chat-dots me-2"></i>Kolom Komentar</h4>

                <form method="post" class="mb-4">
                    <div class="mb-3">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="isi_komentar" rows="4" class="form-control" placeholder="Tulis komentar Anda..." required></textarea>
                    </div>
                    <button type="submit" name="submit_komentar" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Kirim Komentar
                    </button>
                </form>

                <?php if (mysqli_num_rows($komentar) > 0) { ?>
                    <?php while ($k = mysqli_fetch_assoc($komentar)) { ?>
                        <div class="komentar-item">
                            <div class="komentar-nama"><?php echo htmlspecialchars($k['nama']); ?></div>
                            <div class="komentar-tanggal"><?php echo date("d M Y H:i", strtotime($k['tanggal'])); ?></div>
                            <div class="komentar-isi"><?php echo nl2br(htmlspecialchars($k['isi_komentar'])); ?></div>
                        </div>
                    <?php } ?>
                <?php } else { ?>  
                    <p class="text-muted">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                <?php } ?>
            </div>

            <div class="extra-section">
                <h4><i class="bi bi-newspaper me-2"></i>Berita Lainnya</h4>
                <?php if (mysqli_num_rows($beritaLain) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($beritaLain)) { ?>
                        <a href="detail_berita.php?id=<?php echo $row['id_berita']; ?>" class="sidebar-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                            <span><?php echo htmlspecialchars($row['judul']); ?></span>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">Tidak ada berita lain tersedia.</p>
                <?php } ?>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>