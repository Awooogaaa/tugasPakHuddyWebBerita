<?php 
// sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #2563eb;
    --secondary-color: #1e40af;
    --accent-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --text-muted: #9ca3af;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
    --border-color: #e5e7eb;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
}

/* === BAGIAN INTI UNTUK SIDEBAR TOGGLE === */
.d-flex#wrapper {
    transition: padding-left 0.3s ease;
}
.sidebar-modern {
    width: 280px;
    min-width: 280px; /* Mencegah sidebar menyusut */
    min-height: 100vh;
    background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    transition: margin-left 0.3s ease;
    box-shadow: var(--shadow-lg);
    z-index: 1000;
}
#wrapper.toggled .sidebar-modern {
    margin-left: -280px; /* Menyembunyikan sidebar ke kiri */
}
.sidebar-toggle-btn {
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 1050;
    background-color: var(--primary-color);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}
.sidebar-toggle-btn:hover {
    background-color: var(--secondary-color);
    transform: scale(1.1);
}
/* === AKHIR BAGIAN INTI === */


.sidebar-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}
.sidebar-header {
    padding: 2rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
}
.sidebar-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.sidebar-nav { padding: 1.5rem 0; position: relative; z-index: 1; }
.sidebar-nav-item { margin: 0.25rem 1rem; }
.sidebar-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.25rem; color: rgba(255, 255, 255, 0.9); text-decoration: none; border-radius: var(--radius-lg); font-weight: 500; transition: all 0.3s ease; position: relative; overflow: hidden; }
.sidebar-nav-link::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent); transition: left 0.5s ease; }
.sidebar-nav-link:hover::before { left: 100%; }
.sidebar-nav-link:hover { background: rgba(255, 255, 255, 0.15); color: white; transform: translateX(4px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
.sidebar-nav-link.active { background: rgba(255, 255, 255, 0.25); color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.25); transform: translateX(6px); font-weight: 600; }
.sidebar-nav-link.active .sidebar-nav-icon { color: #fff; }
.sidebar-nav-link.logout { color: #fca5a5; margin-top: 1rem; }
.sidebar-nav-link.logout:hover { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
.sidebar-nav-icon { font-size: 1.125rem; width: 20px; text-align: center; }
.main-content { flex: 1; padding: 2rem; background: var(--bg-light); min-height: 100vh; }

/* Global form styles (dikembalikan) */
.form-modern { background: var(--bg-white); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.form-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 600; color: var(--text-dark); margin-bottom: 1.5rem; text-align: center; }
.form-group { margin-bottom: 1.5rem; }
.form-label { font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem; display: block; }
.form-control-modern { width: 100%; padding: 0.875rem 1rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.3s ease; background: var(--bg-white); }
.form-control-modern:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
.btn-modern { padding: 0.875rem 2rem; border: none; border-radius: var(--radius-md); font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; justify-content: center; }
.btn-primary-modern { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; }
.btn-primary-modern:hover { background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)); transform: translateY(-2px); box-shadow: var(--shadow-lg); color: white; }

/* Global table styles (dikembalikan) */
.table-modern { background: var(--bg-white); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.table-modern table { width: 100%; border-collapse: collapse; margin: 0; }
.table-modern th { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 1rem; font-weight: 600; text-align: left; border: none; }
.table-modern td { padding: 1rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
.table-modern tr:last-child td { border-bottom: none; }
.table-modern tr:hover { background: rgba(37, 99, 235, 0.05); }

/* Global card styles (dikembalikan) */
.card-modern { background: var(--bg-white); border-radius: var(--radius-xl); padding: 1.5rem; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); transition: all 0.3s ease; }
.card-modern:hover { transform: translateY(-4px); box-shadow: var(--shadow-xl); }
.page-title { font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 600; color: var(--text-dark); margin-bottom: 2rem; text-align: center; }
.page-title::after { content: ''; display: block; width: 80px; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); margin: 1rem auto 0; border-radius: 2px; }

@media (max-width: 992px) {
    #wrapper.toggled .sidebar-modern {
        margin-left: 0; /* Di layar kecil, kembalikan margin */
    }
    #wrapper .sidebar-modern {
        margin-left: -280px; /* Sembunyikan secara default */
    }
}
</style>

<button class="sidebar-toggle-btn" id="sidebarToggle">
    <i class="bi bi-list"></i>
</button>

<div class="d-flex" id="wrapper">
    <div class="sidebar-modern">
        <div class="sidebar-header">
            <h4 class="sidebar-title">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </h4>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-nav-item">
                <a href="profile.php" class="sidebar-nav-link <?= ($current_page=='profile.php') ? 'active' : '' ?>">
                    <i class="bi bi-person-circle sidebar-nav-icon"></i>
                    <span>Profile</span>
                </a>
            </div>
            <div class="sidebar-nav-item">
                <a href="berita.php" class="sidebar-nav-link <?= ($current_page=='berita.php') ? 'active' : '' ?>">
                    <i class="bi bi-house sidebar-nav-icon"></i>
                    <span>Beranda</span>
                </a>
            </div>
            <div class="sidebar-nav-item">
                <a href="indexberita.php" class="sidebar-nav-link <?= ($current_page=='indexberita.php') ? 'active' : '' ?>">
                    <i class="bi bi-card-list sidebar-nav-icon"></i>
                    <span>Daftar Berita</span>
                </a>
            </div>
            <div class="sidebar-nav-item">
                <a href="indexkategori.php" class="sidebar-nav-link <?= ($current_page=='indexkategori.php') ? 'active' : '' ?>">
                    <i class="bi bi-folder sidebar-nav-icon"></i>
                    <span>Kategori Berita</span>
                </a>
            </div>
            <div class="sidebar-nav-item">
                <a href="logout.php" class="sidebar-nav-link logout">
                    <i class="bi bi-box-arrow-right sidebar-nav-icon"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebarWrapper = document.getElementById('wrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Fungsi untuk toggle sidebar
    function toggleSidebar() {
        sidebarWrapper.classList.toggle('toggled');
        // Simpan state ke localStorage agar pilihan pengguna diingat
        localStorage.setItem('sidebar-toggled', sidebarWrapper.classList.contains('toggled'));
    }

    // Cek state dari localStorage saat halaman dimuat
    if (localStorage.getItem('sidebar-toggled') === 'true') {
        sidebarWrapper.classList.add('toggled');
    }

    // Tambahkan event listener ke tombol
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
});
</script>`