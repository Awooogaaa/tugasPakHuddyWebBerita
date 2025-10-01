<?php
include "koneksi.php";
include "sidebar.php";

$success_message = '';
$error_message = '';

if (isset($_POST['simpan'])) {
    $nama_kategori = trim(mysqli_real_escape_string($conn, $_POST['nama_kategori']));

    // Cek apakah kategori sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE LOWER(nama_kategori) = LOWER('$nama_kategori')");
    if (mysqli_num_rows($cek) > 0) {
        $error_message = "⚠️ Kategori <b>" . htmlspecialchars($nama_kategori) . "</b> sudah ada, gunakan nama lain!";
    } else {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        if (mysqli_query($conn, $query)) {
            // Set success message and redirect with JavaScript
            echo "<script>
                    alert('Kategori berhasil ditambahkan!');
                    window.location.href='indexkategori.php';
                  </script>";
            exit;
        } else {
            $error_message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
    <style>
        /* Mengadopsi style dari form berita */
        .btn-primary-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            padding: 0.8rem 1.8rem;
            font-size: 1.05rem;
            font-weight: 600;
            border: none;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
            text-decoration: none;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.5);
            color: #fff;
        }
        
        .btn-secondary-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            color: #64748b;
            padding: 0.8rem 1.8rem;
            font-size: 1.05rem;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-secondary-modern:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .alert-modern {
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            border: none;
            box-shadow: var(--shadow-md);
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Tambah Kategori Baru</h2>

    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-warning alert-modern"><?= $error_message; ?></div>
    <?php endif; ?>

    <div class="form-modern">
        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-folder me-2"></i>Nama Kategori
                </label>
                <input type="text" name="nama_kategori" class="form-control-modern" required placeholder="Contoh: Teknologi, Olahraga, dll...">
            </div>

            <div class="mt-4 text-center">
                <button type="submit" name="simpan" class="btn-primary-modern">
                    <i class="bi bi-save me-2"></i>Simpan Kategori
                </button>
                 <a href="indexkategori.php" class="btn-secondary-modern ms-3">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

</div> </div> </body>
</html>