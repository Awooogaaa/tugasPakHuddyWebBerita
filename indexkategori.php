<?php
include "koneksi.php";
include "sidebar.php";

// 1. KONFIGURASI PAGINASI
// --------------------------------------------------
$per_halaman = 5; // Jumlah kategori per halaman
$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman_aktif - 1) * $per_halaman;

// 2. HITUNG TOTAL KATEGORI DAN JUMLAH HALAMAN
// --------------------------------------------------
$query_total = mysqli_query($conn, "SELECT id_kategori FROM kategori");
$jumlah_kategori = mysqli_num_rows($query_total);
$jumlah_halaman = ceil($jumlah_kategori / $per_halaman);

// 3. AMBIL DATA KATEGORI DENGAN LIMIT
// --------------------------------------------------
$result = mysqli_query($conn, "
    SELECT * FROM kategori 
    ORDER BY id_kategori DESC 
    LIMIT $mulai, $per_halaman
");
?>

<h2 class="page-title">Kelola Kategori</h2>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="stats-card">
        <i class="bi bi-folder text-primary"></i>
        <div>
            <div class="stats-number"><?= $jumlah_kategori; ?></div>
            <div class="stats-label">Total Kategori</div>
        </div>
    </div>

    <a href="tambahkategori.php" class="btn-add-modern">
        <i class="bi bi-plus-circle"></i>
        Tambah Kategori Baru
    </a>
</div>

<div class="table-modern">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px;">No</th>
                <th>Nama Kategori</th>
                <th style="width: 200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Sesuaikan nomor urut berdasarkan halaman aktif
            $no = $mulai + 1;
            while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td class="text-center fw-bold"><?= $no++; ?></td>
                <td>
                    <div class="news-title-cell">
                        <i class="bi bi-folder me-2 text-primary"></i>
                        <?= htmlspecialchars($row['nama_kategori']); ?>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="editkategori.php?id=<?= $row['id_kategori']; ?>" class="btn-sm btn-warning-modern" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="deleteCategory(<?= $row['id_kategori']; ?>)" class="btn-sm btn-danger-modern" title="Hapus">
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
/* ... (SEMUA CSS LAMA ANDA TETAP DI SINI, TIDAK PERLU DIUBAH) ... */
.stats-card{background:var(--bg-white);padding:1.5rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);border:1px solid var(--border-color);display:flex;align-items:center;gap:1rem;min-width:180px}.stats-card i{font-size:2rem;opacity:.8}.stats-number{font-size:1.5rem;font-weight:700;color:var(--text-dark);line-height:1}.stats-label{font-size:.875rem;color:var(--text-light);font-weight:500}.btn-add-modern{background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));color:#fff;font-weight:600;padding:.8rem 1.5rem;border-radius:50px;display:inline-flex;align-items:center;gap:.5rem;text-decoration:none;font-size:.95rem;transition:all .3s ease;box-shadow:0 4px 10px rgba(0,0,0,.15);border:none}.btn-add-modern i{font-size:1.2rem}.btn-add-modern:hover{transform:translateY(-3px) scale(1.03);box-shadow:0 6px 14px rgba(0,0,0,.2);filter:brightness(1.1)}.btn-add-modern:active{transform:translateY(0) scale(.98);box-shadow:0 3px 8px rgba(0,0,0,.15)}.news-title-cell{font-weight:600;color:var(--text-dark);font-size:1.05rem;line-height:1.4;display:flex;align-items:center}.action-buttons{display:flex;gap:.5rem;justify-content:center}.btn-sm{padding:.5rem;border-radius:var(--radius-md);border:none;cursor:pointer;transition:all .3s ease;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;font-size:.875rem}.btn-warning-modern{background:var(--warning-color);color:#fff}.btn-warning-modern:hover{background:#d97706;transform:translateY(-2px);box-shadow:var(--shadow-md);color:#fff}.btn-danger-modern{background:var(--danger-color);color:#fff}.btn-danger-modern:hover{background:#dc2626;transform:translateY(-2px);box-shadow:var(--shadow-md);color:#fff}

/* Tambahan CSS untuk Paginasi agar terlihat bagus */
.pagination{--bs-pagination-padding-x:0.75rem;--bs-pagination-padding-y:0.375rem;--bs-pagination-font-size:1rem;--bs-pagination-color:var(--primary-color);--bs-pagination-bg:#fff;--bs-pagination-border-width:1px;--bs-pagination-border-color:#dee2e6;--bs-pagination-border-radius:0.375rem;--bs-pagination-hover-color:#0a58ca;--bs-pagination-hover-bg:#e9ecef;--bs-pagination-hover-border-color:#dee2e6;--bs-pagination-focus-color:#0a58ca;--bs-pagination-focus-bg:#e9ecef;--bs-pagination-focus-box-shadow:0 0 0 0.25rem rgba(13,110,253,.25);--bs-pagination-active-color:#fff;--bs-pagination-active-bg:var(--primary-color);--bs-pagination-active-border-color:var(--primary-color);--bs-pagination-disabled-color:#6c757d;--bs-pagination-disabled-bg:#fff;--bs-pagination-disabled-border-color:#dee2e6;display:flex;padding-left:0;list-style:none}.page-link{position:relative;display:block;padding:var(--bs-pagination-padding-y) var(--bs-pagination-padding-x);font-size:var(--bs-pagination-font-size);color:var(--bs-pagination-color);text-decoration:none;background-color:var(--bs-pagination-bg);border:var(--bs-pagination-border-width) solid var(--bs-pagination-border-color);transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}.page-item:first-child .page-link{border-top-left-radius:var(--bs-pagination-border-radius);border-bottom-left-radius:var(--bs-pagination-border-radius)}.page-item:last-child .page-link{border-top-right-radius:var(--bs-pagination-border-radius);border-bottom-right-radius:var(--bs-pagination-border-radius)}.page-item.active .page-link{z-index:3;color:var(--bs-pagination-active-color);background-color:var(--bs-pagination-active-bg);border-color:var(--bs-pagination-active-border-color)}.page-item.disabled .page-link{color:var(--bs-pagination-disabled-color);pointer-events:none;background-color:var(--bs-pagination-disabled-bg);border-color:var(--bs-pagination-disabled-border-color)}
</style>

<script>
function deleteCategory(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua berita terkait akan ikut terhapus!')) {
        window.location.href = 'hapuskategori.php?id=' + id;
    }
}
</script>

</div> </div>