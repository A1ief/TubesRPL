<?php
include('../../../koneksi.php');

// Ambil ID dari query string
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// Ambil data lama
$pembayaran = $koneksi->query("SELECT * FROM tbl_pembayaran WHERE id_pembayaran = '$id'")->fetch_assoc();
$dokter = $koneksi->query("SELECT id_dokter, nama_lengkap FROM tbl_dokter");
$pasien = $koneksi->query("SELECT id_pasien, nama_pasien FROM tbl_pasien");
$admin = $koneksi->query("SELECT id_admin, nama_admin FROM tbl_admin");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $id_admin = $_POST['id_admin'];
    $tanggal = $_POST['tanggal_pembayaran'];
    $total = $_POST['total_pembayaran'];

    $update = $koneksi->query("UPDATE tbl_pembayaran SET 
        id_dokter = '$id_dokter', 
        id_pasien = '$id_pasien', 
        id_admin = '$id_admin',
        tanggal_pembayaran = '$tanggal',
        total_pembayaran = '$total'
        WHERE id_pembayaran = '$id'
    ");

    if ($update) {
        header("Location: index.php");
    } else {
        echo "<script>alert('Gagal mengubah data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Edit Pembayaran</h1>

                    <form method="POST" class="col-md-12">
                        <div class="form-group">
                            <label for="tanggal_pembayaran">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" name="tanggal_pembayaran" value="<?= $pembayaran['tanggal_pembayaran'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="total_pembayaran">Total Pembayaran</label>
                            <input type="number" class="form-control" name="total_pembayaran" value="<?= $pembayaran['total_pembayaran'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="id_dokter">Dokter</label>
                            <select name="id_dokter" class="form-control" required>
                                <option value="">-- Pilih Dokter --</option>
                                <?php while ($d = $dokter->fetch_assoc()) : ?>
                                    <option value="<?= $d['id_dokter'] ?>" <?= ($d['id_dokter'] == $pembayaran['id_dokter']) ? 'selected' : '' ?>>
                                        <?= $d['nama_lengkap'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_pasien">Pasien</label>
                            <select name="id_pasien" class="form-control" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php while ($p = $pasien->fetch_assoc()) : ?>
                                    <option value="<?= $p['id_pasien'] ?>" <?= ($p['id_pasien'] == $pembayaran['id_pasien']) ? 'selected' : '' ?>>
                                        <?= $p['nama_pasien'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_admin">Admin</label>
                            <select name="id_admin" class="form-control" required>
                                <option value="">-- Pilih Admin --</option>
                                <?php while ($a = $admin->fetch_assoc()) : ?>
                                    <option value="<?= $a['id_admin'] ?>" <?= ($a['id_admin'] == $pembayaran['id_admin']) ? 'selected' : '' ?>>
                                        <?= $a['nama_admin'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

            <footer class="sticky-footer bg-white mt-5">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?php include('../../tamplates/script.php'); ?>
</body>

</html>
