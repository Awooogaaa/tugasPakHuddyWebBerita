<?php
include "koneksi.php";
include "sidebar.php";

// ambil kategori untuk dropdown
$kategoriResult = mysqli_query($conn, "SELECT * FROM kategori");

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_kategori = $_POST['id_kategori'];

    // upload gambar
    $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));

    $query = "INSERT INTO berita (judul, isi, gambar, id_kategori) 
              VALUES ('$judul', '$isi', '$gambar', '$id_kategori')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success alert-modern'><i class='bi bi-check-circle me-2'></i>Berita berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger alert-modern'><i class='bi bi-exclamation-triangle me-2'></i>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Berita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Enhanced styling with modern design system -->
  <style>
    
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
}

.btn-primary-modern:hover {
  background: linear-gradient(135deg, #1e40af, #1d4ed8);
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.5);
}

.btn-primary-modern:active {
  transform: scale(0.97);
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
}

.btn-primary-modern i {
  font-size: 1.2rem;
}

    .alert-modern {
      border-radius: 1rem;
      padding: 1rem 1.5rem;
      border: none;
      box-shadow: var(--shadow-md);
      font-weight: 500;
    }
    
    .drop-zone {
      border: 3px dashed var(--primary-color);
      border-radius: 1.5rem;
      padding: 3rem 2rem;
      text-align: center;
      cursor: pointer;
      color: var(--text-light);
      background: rgba(37, 99, 235, 0.02);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .drop-zone::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
      transition: left 0.5s ease;
    }
    
    .drop-zone:hover::before {
      left: 100%;
    }
    
    .drop-zone.dragover {
      background: rgba(37, 99, 235, 0.1);
      border-color: var(--secondary-color);
      color: var(--primary-color);
      transform: scale(1.02);
    }
    
    .drop-zone img {
      max-width: 200px;
      max-height: 200px;
      margin-top: 1rem;
      border-radius: 1rem;
      box-shadow: var(--shadow-md);
      object-fit: cover;
    }
    
    .drop-zone-icon {
      font-size: 3rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }
    
    .drop-zone-text {
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .drop-zone-subtext {
      font-size: 0.9rem;
      color: var(--text-light);
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <h2 class="page-title">Tambah Berita Baru</h2>

  <div class="form-modern">
    <form method="POST" action="" enctype="multipart/form-data">
      <div class="form-group">
        <label class="form-label">
          <i class="bi bi-card-heading me-2"></i>Judul Berita
        </label>
        <input type="text" name="judul" class="form-control-modern" required placeholder="Masukkan judul berita yang menarik">
      </div>

      <div class="form-group">
        <label class="form-label">
          <i class="bi bi-file-text me-2"></i>Isi Berita
        </label>
        <textarea name="isi" rows="8" class="form-control-modern" required placeholder="Tulis isi berita secara lengkap dan informatif..."></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">
          <i class="bi bi-image me-2"></i>Upload Gambar
        </label>
        <div class="drop-zone" id="dropZone">
          <div class="drop-zone-icon">
            <i class="bi bi-cloud-upload"></i>
          </div>
          <p class="drop-zone-text">Drag & Drop gambar di sini</p>
          <p class="drop-zone-subtext">atau klik untuk memilih file</p>
          <input type="file" name="gambar" id="gambarInput" class="form-control d-none" accept="image/*" required>
          <img id="preview" src="#" alt="Preview" class="d-none">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">
          <i class="bi bi-folder me-2"></i>Kategori
        </label>
        <select name="id_kategori" class="form-control-modern" required>
          <option value="">-- Pilih Kategori Berita --</option>
          <?php while($row = mysqli_fetch_assoc($kategoriResult)) { ?>
            <option value="<?= $row['id_kategori']; ?>"><?= $row['nama_kategori']; ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="text-center">
        <button type="submit" name="simpan" class="btn-primary-modern">
          <i class="bi bi-save me-2"></i>Simpan Berita
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const dropZone = document.getElementById("dropZone");
  const fileInput = document.getElementById("gambarInput");
  const preview = document.getElementById("preview");

  dropZone.addEventListener("click", () => fileInput.click());

  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      updatePreview(fileInput.files[0]);
    }
  });

  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragover");
  });

  dropZone.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragover");
  });

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragover");
    if (e.dataTransfer.files.length > 0) {
      fileInput.files = e.dataTransfer.files;
      updatePreview(e.dataTransfer.files[0]);
    }
  });

  function updatePreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.src = e.target.result;
      preview.classList.remove("d-none");
      dropZone.querySelector('.drop-zone-icon').style.display = 'none';
      dropZone.querySelector('.drop-zone-text').textContent = 'Gambar berhasil dipilih';
      dropZone.querySelector('.drop-zone-subtext').textContent = 'Klik untuk mengganti gambar';
    };
    reader.readAsDataURL(file);
  }
</script>

</div> <!-- Tutup div konten dari sidebar.php -->
</div> <!-- Tutup div flex dari sidebar.php -->

</body>
</html>
