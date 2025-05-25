<?php
include '../../../koneksi.php';

// Pastikan parameter ID ada
// get mengambil id 
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Jalankan query hapus
    $hapus = $koneksi->query("DELETE FROM rekammedis WHERE id_rekammedis = $id");

    // Redirect kembali ke index
    if ($hapus) {
        header('Location: index.php');
    } else {
        echo "Gagal menghapus data."; // gagal menghapus  eror 
    }
} else {
    echo "ID tidak ditemukan.";
}
?>