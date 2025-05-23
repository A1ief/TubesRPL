<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('../../../koneksi.php');

// Validasi parameter ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Ambil data obat berdasarkan ID
$stmt = $koneksi->prepare("SELECT * FROM tbl_obat WHERE id_obat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Update data jika form disubmit
if (isset($_POST['update'])) {
    $nama_obat  = $_POST['nama_obat'];
    $dosis_obat = $_POST['dosis_obat'];

    $stmt = $koneksi->prepare("UPDATE tbl_obat SET nama_obat = ?, dosis_obat = ? WHERE id_obat = ?");
    $stmt->bind_param("ssi", $nama_obat, $dosis_obat, $id);
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Obat</title>

    <!-- Fonts & Styles -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Obat</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" class="mt-4">
                            <div class="mb-3">
                                <label>Nama Obat</label>
                                <input type="text" name="nama_obat" class="form-control" value="<?= $data['nama_obat'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Dosis Obat</label>
                                <input type="text" name="dosis_obat" value="<?= $data['dosis_obat'] ?>" class="form-control">
                            </div>
                            <button type="submit" name="update" class="btn btn-success">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
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

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-double-up"></i>
    </a>

    <?= include('../../tamplates/script.php'); ?>

</body>

</html>