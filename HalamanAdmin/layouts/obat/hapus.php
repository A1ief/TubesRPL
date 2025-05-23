<?php
include '../../../koneksi.php';

// Pastikan parameter ID ada
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Jalankan query hapus
    $hapus = $koneksi->query("DELETE FROM tbl_obat WHERE id_obat = $id");

    // Redirect kembali ke index
    if ($hapus) {
        header('Location: index.php');
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
