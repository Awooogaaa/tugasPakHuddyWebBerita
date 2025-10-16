<?php
session_start();
include "koneksi.php";

$isLogin = isset($_SESSION['username']);
$nama_website = "Radar Berita Dlanggu";

// --- LOGIKA PENCARIAN ---
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
}
$search_sql_where = '';
if (!empty($search_term)) {
    $search_sql_where = " WHERE (b.judul LIKE '%$search_term%' OR b.isi LIKE '%$search_term%')";
}

// --- PENGATURAN PAGINASI ---
$berita_per_halaman = 6;
$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman_aktif - 1) * $berita_per_halaman;

// --- HITUNG TOTAL BERITA (SESUAI PENCARIAN) ---
$total_query = "SELECT COUNT(b.id_berita) AS total FROM berita b JOIN kategori k ON b.id_kategori = k.id_kategori" . $search_sql_where;
$total_result = mysqli_query($conn, $total_query);
$total_berita = mysqli_fetch_assoc($total_result)['total'];
$jumlah_halaman = ceil($total_berita / $berita_per_halaman);

// --- AMBIL BERITA HEADLINE ---
$headline = null;
if (empty($search_term)) {
    $headline_result = mysqli_query($conn, "SELECT b.*, k.nama_kategori FROM berita b JOIN kategori k ON b.id_kategori = k.id_kategori ORDER BY b.id_berita DESC LIMIT 1");
    $headline = mysqli_fetch_assoc($headline_result);
}

// --- AMBIL BERITA UTAMA ---
$berita_query = "
    SELECT b.*, k.nama_kategori 
    FROM berita b
    JOIN kategori k ON b.id_kategori = k.id_kategori
    " . $search_sql_where . "
    ORDER BY b.id_berita DESC
    LIMIT $mulai, $berita_per_halaman
";
$result = mysqli_query($conn, $berita_query);

// --- AMBIL BERITA POPULER ---
$popular_result = mysqli_query($conn, "SELECT id_berita, judul, gambar FROM berita ORDER BY views DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $nama_website ?> - Berita Terkini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --text-dark: #212529;
            --bg-light: #f8f9fa;
            --font-primary: 'Poppins', sans-serif;
            --font-heading: 'Playfair Display', serif;
            --border-radius: 0.5rem;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
        }
        body { font-family: var(--font-primary); background-color: var(--bg-light); color: var(--text-dark); }
        .page-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .content-wrapper { flex: 1; }
        .navbar-custom { background-color: #fff; border-bottom: 1px solid #dee2e6; box-shadow: var(--shadow-sm); }
        .navbar-brand { font-family: var(--font-heading); font-weight: 800; color: var(--text-dark) !important; font-size: 1.5rem; }
        .headline-section { height: 60vh; max-height: 450px; position: relative; border-radius: var(--border-radius); overflow: hidden; color: white; }
        .headline-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.5); }
        .headline-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; padding: 2rem; }
        .category-badge { background-color: var(--primary-color); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .headline-title { font-family: var(--font-heading); font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 700; line-height: 1.2; text-shadow: 1px 1px 4px rgba(0,0,0,0.6); }
        .headline-title a { color: white; text-decoration: none; }
        .section-title { font-family: var(--font-heading); font-weight: 700; font-size: 1.8rem; border-bottom: 3px solid var(--primary-color); padding-bottom: 0.5rem; display: inline-block; }
        .news-card { background-color: #fff; border: 1px solid #e0e0e0; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); transition: all 0.3s ease; height: 100%; }
        .news-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .news-card-img { width: 100%; height: 200px; object-fit: cover; border-top-left-radius: var(--border-radius); border-top-right-radius: var(--border-radius); }
        .news-card-title { font-weight: 600; font-size: 1.1rem; color: var(--text-dark); text-decoration: none; }
        .widget { background-color: #fff; padding: 1.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); border: 1px solid #e0e0e0; }
        .popular-post-item { display: flex; align-items: flex-start; gap: 1rem; }
        .popular-post-item img { width: 80px; height: 60px; object-fit: cover; border-radius: 0.25rem; }
        .popular-post-title { font-weight: 600; font-size: 0.95rem; line-height: 1.4; color: var(--text-dark); text-decoration: none; }
        
        /* Styles for Admin Layout */
        #wrapper { display: flex; width: 100%; }
        .main-content { flex-grow: 1; }
        .page-title { font-family: var(--font-heading); }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="content-wrapper">
        <?php if ($isLogin): ?>
            <div id="wrapper">
                <?php include "sidebar.php"; ?>
                <main class="main-content p-4">
        <?php else: ?>
            <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
                <div class="container">
                    <a class="navbar-brand" href="berita.php"><i class="bi bi-newspaper"></i> <?= $nama_website ?></a>
                    <div class="ms-auto d-flex align-items-center">
                        <form class="d-flex me-3" action="berita.php" method="GET">
                            <input class="form-control form-control-sm rounded-pill" type="search" name="search" placeholder="Cari berita..." value="<?= htmlspecialchars($search_term); ?>">
                            <button class="btn btn-sm btn-outline-primary rounded-pill ms-2" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
                    </div>
                </div>
            </nav>
            <main class="container py-4">
        <?php endif; ?>

            <?php if ($isLogin) : ?>
                <h1 class="page-title mb-4">Beranda</h1>
            <?php endif; ?>

            <?php if ($headline && empty($search_term)) : ?>
            <header class="headline-section mb-5">
                <img src="data:image/jpeg;base64,<?= base64_encode($headline['gambar']); ?>" class="headline-bg" alt="Headline">
                <div class="headline-content">
                    <div>
                        <span class="category-badge"><?= htmlspecialchars($headline['nama_kategori']); ?></span>
                        <h1 class="headline-title"><a href="detail_berita.php?id=<?= $headline['id_berita']; ?>"><?= htmlspecialchars($headline['judul']); ?></a></h1>
                    </div>
                </div>
            </header>
            <?php endif; ?>

            <div class="row g-5">
                <div class="<?php echo !empty($search_term) ? 'col-12' : 'col-lg-8'; ?>">
                    <h2 class="section-title mb-4">
                        <?= !empty($search_term) ? 'Hasil Pencarian: "' . htmlspecialchars($search_term) . '"' : 'Berita Terbaru'; ?>
                    </h2>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                    <div class="row g-4">
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="<?php echo !empty($search_term) ? 'col-lg-4 col-md-6' : 'col-md-6'; ?> d-flex align-items-stretch">
                            <div class="card news-card w-100">
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']); ?>" class="news-card-img" alt="<?= htmlspecialchars($row['judul']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <a href="detail_berita.php?id=<?= $row['id_berita']; ?>" class="news-card-title"><?= htmlspecialchars($row['judul']); ?></a>
                                    <p class="news-card-excerpt mt-2 mb-0"><?= htmlspecialchars(substr(strip_tags($row['isi']), 0, 100)); ?>...</p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning text-center">Tidak ada berita yang ditemukan.</div>
                    <?php endif; ?>
                    <nav class="mt-5 d-flex justify-content-center">
                        <ul class="pagination">
                            <?php 
                            $search_query_string = !empty($search_term) ? '&search=' . urlencode($search_term) : '';
                            for ($i = 1; $i <= $jumlah_halaman; $i++) : ?>
                            <li class="page-item <?= ($i == $halaman_aktif) ? 'active' : '' ?>">
                                <a class="page-link" href="?halaman=<?= $i . $search_query_string ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>

                <?php if (empty($search_term)) : ?>
                <div class="col-lg-4">
                    <div class="widget">
                        <h3 class="section-title mb-4">Berita Populer</h3>
                        <div class="vstack gap-4">
                            <?php while ($popular = mysqli_fetch_assoc($popular_result)): ?>
                            <div class="popular-post-item">
                                <img src="data:image/jpeg;base64,<?= base64_encode($popular['gambar']); ?>" alt="Populer">
                                <div><a href="detail_berita.php?id=<?= $popular['id_berita']; ?>" class="popular-post-title"><?= htmlspecialchars($popular['judul']); ?></a></div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
        </main>
        <?php if ($isLogin): ?></div><?php endif; // Penutup #wrapper ?>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-auto">
        <p class="mb-0">&copy; <?= date('Y') ?> <?= $nama_website ?>. All Rights Reserved.</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebarWrapper = document.getElementById('wrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');

    function toggleSidebar() {
        if (sidebarWrapper) {
            sidebarWrapper.classList.toggle('toggled');
            localStorage.setItem('sidebar-toggled', sidebarWrapper.classList.contains('toggled'));
        }
    }

    if (localStorage.getItem('sidebar-toggled') === 'true' && sidebarWrapper) {
        sidebarWrapper.classList.add('toggled');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
});
</script>
</body>
</html>