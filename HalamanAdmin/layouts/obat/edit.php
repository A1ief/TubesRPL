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
    $harga = $_POST['harga'];

    $stmt = $koneksi->prepare("UPDATE tbl_obat SET nama_obat = ?, dosis_obat = ?, harga = ? WHERE id_obat = ?");
    $stmt->bind_param("ssii", $nama_obat, $dosis_obat, $harga, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<?php include('../../tamplates/head.php'); ?>

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
                                <input type="number" name="dosis_obat" value="<?= $data['dosis_obat'] ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Harga</label>
                                <input type="number" name="harga" value="<?= $data['harga'] ?>" class="form-control" required>
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