<?php
session_start();
include "koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = intval($_GET['id']);

// Menambahkan 1 view setiap kali berita dibuka
mysqli_query($conn, "UPDATE berita SET views = views + 1 WHERE id_berita = $id");

$result = mysqli_query($conn, "SELECT b.*, k.nama_kategori 
                                FROM berita b 
                                JOIN kategori k ON b.id_kategori = k.id_kategori 
                                WHERE b.id_berita = $id");
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
        header("Location: detail_berita.php?id=" . $id . "#kolom-komentar");
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --text-dark: #212529;
            --text-light: #6c757d;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --font-primary: 'Poppins', sans-serif;
            --font-heading: 'Playfair Display', serif;
            --border-radius: 0.5rem;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
        }
        body { font-family: var(--font-primary); background-color: var(--bg-light); color: var(--text-dark); line-height: 1.7; }
        .navbar-custom { background-color: var(--bg-white); border-bottom: 1px solid #dee2e6; box-shadow: var(--shadow-sm); }
        .navbar-brand { font-family: var(--font-heading); font-weight: 800; color: var(--text-dark) !important; }
        .article-header { padding: 3rem 0; }
        .category-badge { background-color: var(--primary-color); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; text-decoration: none; display: inline-block; margin-bottom: 1rem; }
        .article-title { font-family: var(--font-heading); font-size: clamp(2rem, 5vw, 3rem); font-weight: 800; line-height: 1.2; }
        .article-meta { color: var(--text-light); font-size: 0.9rem; }
        .article-image { width: 100%; height: auto; max-height: 500px; object-fit: cover; border-radius: var(--border-radius); box-shadow: var(--shadow-md); }
        .article-content { font-size: 1.1rem; }
        .widget { background-color: var(--bg-white); padding: 1.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); }
        .section-title { font-family: var(--font-heading); font-weight: 700; font-size: 1.5rem; border-bottom: 3px solid var(--primary-color); padding-bottom: 0.5rem; display: inline-block; }
        .comment-card { background-color: var(--bg-white); padding: 1.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); }
        .comment-item { border-bottom: 1px solid #e9ecef; padding: 1rem 0; }
        .comment-item:last-child { border-bottom: none; }
        .comment-author { font-weight: 600; color: var(--text-dark); }
        .comment-date { font-size: 0.8rem; color: var(--text-light); }
        .popular-post-item { display: flex; align-items: flex-start; gap: 1rem; }
        .popular-post-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 0.25rem; }
        .popular-post-title { font-weight: 600; font-size: 0.95rem; line-height: 1.4; color: var(--text-dark); text-decoration: none; }
        .popular-post-title:hover { color: var(--primary-color); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="berita.php"><i class="bi bi-newspaper me-2"></i>NewsPortal</a>
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
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article>
                <header class="article-header text-center">
                    <a href="#" class="category-badge"><?= htmlspecialchars($berita['nama_kategori']); ?></a>
                    <h1 class="article-title"><?php echo htmlspecialchars($berita['judul']); ?></h1>
                </header>

                <img src="data:image/jpeg;base64,<?php echo base64_encode($berita['gambar']); ?>" 
                     class="article-image mb-4" alt="<?php echo htmlspecialchars($berita['judul']); ?>">
                
                <div class="article-content">
                    <?php echo nl2br(htmlspecialchars($berita['isi'])); ?>
                </div>
            </article>

            <div class="comment-card my-5" id="kolom-komentar">
                <h3 class="section-title mb-4">Kolom Komentar</h3>
                <form method="post" class="mb-4">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi_komentar" class="form-label">Komentar Anda</label>
                        <textarea id="isi_komentar" name="isi_komentar" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="submit_komentar" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Kirim Komentar
                    </button>
                </form>

                <hr>

                <?php if (mysqli_num_rows($komentar) > 0) { ?>
                    <?php while ($k = mysqli_fetch_assoc($komentar)) { ?>
                        <div class="comment-item">
                            <p class="mb-1">
                                <strong class="comment-author"><?php echo htmlspecialchars($k['nama']); ?></strong>
                                <span class="comment-date ms-2"><?php echo date("d M Y, H:i", strtotime($k['tanggal'])); ?></span>
                            </p>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($k['isi_komentar'])); ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>  
                    <p class="text-center text-muted">Jadilah yang pertama berkomentar!</p>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<div class="bg-white py-5">
    <div class="container">
        <h3 class="section-title mb-4">Berita Lainnya</h3>
        <div class="row">
            <?php if (mysqli_num_rows($beritaLain) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($beritaLain)) { ?>
                    <div class="col-lg col-md-4 col-sm-6 mb-3">
                        <div class="popular-post-item">
                             <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                            <div>
                                <a href="detail_berita.php?id=<?php echo $row['id_berita']; ?>" class="popular-post-title">
                                    <?php echo htmlspecialchars($row['judul']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-muted">Tidak ada berita lain tersedia.</p>
            <?php } ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>