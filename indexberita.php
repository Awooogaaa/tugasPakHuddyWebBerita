<?php
include "koneksi.php";
include "sidebar.php";

// 1. KONFIGURASI PAGINASI
// --------------------------------------------------
$per_halaman = 5; // Jumlah berita yang ingin ditampilkan per halaman
$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman_aktif - 1) * $per_halaman;

// 2. HITUNG TOTAL BERITA DAN JUMLAH HALAMAN
// --------------------------------------------------
// Query ini untuk menghitung total semua berita tanpa limit
$query_total = mysqli_query($conn, "SELECT id_berita FROM berita");
$jumlah_berita = mysqli_num_rows($query_total);
$jumlah_halaman = ceil($jumlah_berita / $per_halaman);

// 3. AMBIL DATA BERITA DENGAN LIMIT UNTUK HALAMAN AKTIF
// --------------------------------------------------
// Query utama diubah dengan menambahkan LIMIT
$result = mysqli_query($conn, "
    SELECT b.id_berita, b.judul, b.isi, b.gambar, k.nama_kategori
    FROM berita b
    JOIN kategori k ON b.id_kategori = k.id_kategori
    ORDER BY b.id_berita DESC
    LIMIT $mulai, $per_halaman
");
?>

<h2 class="page-title">Kelola Berita</h2>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="stats-card">
            <i class="bi bi-newspaper text-primary"></i>
            <div>
                <div class="stats-number"><?= $jumlah_berita; ?></div>
                <div class="stats-label">Total Berita</div>
            </div>
        </div>
    </div>
    
    <a href="tambahberita.php" class="btn-add-modern">
        <i class="bi bi-plus-circle"></i>
        Tambah Berita Baru
    </a>
</div>

<div class="table-modern">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th style="width: 30%;">Judul</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 35%;">Isi</th>
                <th style="width: 120px;">Gambar</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Penomoran disesuaikan dengan halaman aktif
            $no = $mulai + 1;
            while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td class="text-center fw-bold"><?= $no++; ?></td>
                    <td>
                        <div class="news-title-cell">
                            <?= $row['judul']; ?>
                        </div>
                    </td>
                    <td>
                        <span class="category-badge">
                            <i class="bi bi-folder me-1"></i>
                            <?= $row['nama_kategori']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="news-content-cell">
                            <?= substr($row['isi'],0,120)."..."; ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if(!empty($row['gambar'])) { ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']); ?>" 
                                 class="news-thumbnail" alt="Thumbnail">
                        <?php } else { ?>
                            <div class="no-image">
                                <i class="bi bi-image"></i>
                                <span>No Image</span>
                            </div>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="editberita.php?id=<?= $row['id_berita']; ?>" class="btn-sm btn-warning-modern" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="detail_berita.php?id=<?= $row['id_berita']; ?>" class="btn-sm btn-info-modern" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button onclick="deleteNews(<?= $row['id_berita']; ?>)" class="btn-sm btn-danger-modern" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
    <ul class="pagination">
        <li class="page-item <?= ($halaman_aktif <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?halaman=<?= $halaman_aktif - 1 ?>">Previous</a>
        </li>
        
        <?php for ($i = 1; $i <= $jumlah_halaman; $i++) : ?>
            <li class="page-item <?= ($i == $halaman_aktif) ? 'active' : '' ?>">
                <a class="page-link" href="?halaman=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        
        <li class="page-item <?= ($halaman_aktif >= $jumlah_halaman) ? 'disabled' : '' ?>">
            <a class="page-link" href="?halaman=<?= $halaman_aktif + 1 ?>">Next</a>
        </li>
    </ul>
</nav>
<style>

.stats-card { background: var(--bg-white); padding: 1.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem; min-width: 180px; }
.stats-card i { font-size: 2rem; opacity: 0.8; }
.btn-add-modern { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; font-weight: 600; padding: 0.8rem 1.5rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.15); border: none; }
.btn-add-modern i { font-size: 1.2rem; }
.btn-add-modern:hover { transform: translateY(-3px) scale(1.03); box-shadow: 0 6px 14px rgba(0,0,0,0.2); filter: brightness(1.1); }
.btn-add-modern:active { transform: translateY(0) scale(0.98); box-shadow: 0 3px 8px rgba(0,0,0,0.15); }
.stats-number { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); line-height: 1; }
.stats-label { font-size: 0.875rem; color: var(--text-light); font-weight: 500; }
.news-title-cell { font-weight: 600; color: var(--text-dark); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.news-content-cell { color: var(--text-light); font-size: 0.9rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.category-badge { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center; white-space: nowrap; }
.news-thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 2px solid var(--border-color); }
.no-image { width: 80px; height: 80px; background: var(--bg-light); border: 2px dashed var(--border-color); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-light); font-size: 0.75rem; }
.no-image i { font-size: 1.5rem; margin-bottom: 0.25rem; }
.action-buttons { display: flex; gap: 0.5rem; justify-content: center; }
.btn-sm { padding: 0.5rem; border-radius: var(--radius-md); border: none; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; font-size: 0.875rem; }
.btn-warning-modern { background: var(--warning-color); color: white; }
.btn-warning-modern:hover { background: #d97706; transform: translateY(-2px); box-shadow: var(--shadow-md); color: white; }
.btn-info-modern { background: #06b6d4; color: white; }
.btn-info-modern:hover { background: #0891b2; transform: translateY(-2px); box-shadow: var(--shadow-md); color: white; }
.btn-danger-modern { background: var(--danger-color); color: white; }
.btn-danger-modern:hover { background: #dc2626; transform: translateY(-2px); box-shadow: var(--shadow-md); color: white; }


.pagination {
    --bs-pagination-padding-x: 0.75rem;
    --bs-pagination-padding-y: 0.375rem;
    --bs-pagination-font-size: 1rem;
    --bs-pagination-color: var(--primary-color);
    --bs-pagination-bg: #fff;
    --bs-pagination-border-width: 1px;
    --bs-pagination-border-color: #dee2e6;
    --bs-pagination-border-radius: 0.375rem;
    --bs-pagination-hover-color: #0a58ca;
    --bs-pagination-hover-bg: #e9ecef;
    --bs-pagination-hover-border-color: #dee2e6;
    --bs-pagination-focus-color: #0a58ca;
    --bs-pagination-focus-bg: #e9ecef;
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: var(--primary-color);
    --bs-pagination-active-border-color: var(--primary-color);
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: #fff;
    --bs-pagination-disabled-border-color: #dee2e6;
    display: flex;
    padding-left: 0;
    list-style: none;
}
.page-link { position: relative; display: block; padding: var(--bs-pagination-padding-y) var(--bs-pagination-padding-x); font-size: var(--bs-pagination-font-size); color: var(--bs-pagination-color); text-decoration: none; background-color: var(--bs-pagination-bg); border: var(--bs-pagination-border-width) solid var(--bs-pagination-border-color); transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out; }
.page-item:first-child .page-link { border-top-left-radius: var(--bs-pagination-border-radius); border-bottom-left-radius: var(--bs-pagination-border-radius); }
.page-item:last-child .page-link { border-top-right-radius: var(--bs-pagination-border-radius); border-bottom-right-radius: var(--bs-pagination-border-radius); }
.page-item.active .page-link { z-index: 3; color: var(--bs-pagination-active-color); background-color: var(--bs-pagination-active-bg); border-color: var(--bs-pagination-active-border-color); }
.page-item.disabled .page-link { color: var(--bs-pagination-disabled-color); pointer-events: none; background-color: var(--bs-pagination-disabled-bg); border-color: var(--bs-pagination-disabled-border-color); }

</style>

<script>
function deleteNews(id) {
    if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
        // Add delete functionality here
        window.location.href = 'hapusberita.php?id=' + id;
    }
}
</script>

</div> </div> ```