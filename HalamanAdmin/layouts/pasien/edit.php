<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('../../../koneksi.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Ambil data pasien berdasarkan ID
$stmt = $koneksi->prepare("SELECT * FROM tbl_pasien WHERE id_pasien = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Update data jika form disubmit
if (isset($_POST['update'])) {
    $nama   = $_POST['nama_pasien'];
    $email  = $_POST['email_pasien'];
    $telp   = $_POST['no_telp'];
    $alamat = $_POST['alamat_pasien'];

    $stmt = $koneksi->prepare("UPDATE tbl_pasien SET nama_pasien = ?, email_pasien = ?, no_telp = ?, alamat_pasien = ? WHERE id_pasien = ?");
    $stmt->bind_param("ssssi", $nama, $email, $telp, $alamat, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Pasien</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include('../../tamplates/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../../tamplates/topbar.php'); ?>
            <div class="container-fluid">
                <h2 class="mt-4">Edit Pasien</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_pasien" class="form-control" value="<?= htmlspecialchars($data['nama_pasien']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email_pasien" class="form-control" value="<?= htmlspecialchars($data['email_pasien']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($data['no_telp']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat_pasien" class="form-control" required><?= htmlspecialchars($data['alamat_pasien']) ?></textarea>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-double-up"></i></a>
<?= include('../../tamplates/script.php'); ?>
</body>
</html>
