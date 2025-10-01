<?php
include "koneksi.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // Verify the news exists before deleting
    $checkQuery = "SELECT id_berita FROM berita WHERE id_berita = $id";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Delete the news article
        $deleteQuery = "DELETE FROM berita WHERE id_berita = $id";
        
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>
                    alert('Berita berhasil dihapus!');
                    window.location.href = 'indexberita.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: Gagal menghapus berita!');
                    window.location.href = 'indexberita.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Berita tidak ditemukan!');
                window.location.href = 'indexberita.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID berita tidak valid!');
            window.location.href = 'indexberita.php';
          </script>";
}
?>
