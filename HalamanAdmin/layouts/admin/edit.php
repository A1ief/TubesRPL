<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('../../../koneksi.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Ambil data admin berdasarkan ID
$stmt = $koneksi->prepare("SELECT * FROM tbl_admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Update data jika form disubmit
if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama_admin'];
    $email  = $_POST['email_admin'];
    $telp   = $_POST['no_telp'];
    $alamat = $_POST['alamat_admin'];

    $stmt = $koneksi->prepare("UPDATE tbl_admin SET nama_admin = ?, email_admin = ?, no_telp = ?, alamat_admin = ? WHERE id_admin = ?");
    $stmt->bind_param("ssssi", $nama, $email, $telp, $alamat, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<?php include('../../tamplates/head.php'); ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('../../tamplates/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('../../tamplates/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content -->
                    <div class="card-body">
                        <h2>Tambah Admin</h2>
                        <form method="POST" class="mt-4">
                            <div class="mb-3">
                                <label>Nama Admin</label>
                                <input type="text" name="nama_admin" class="form-control" value="<?= htmlspecialchars($data['nama_admin']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Email Admin</label>
                                <input type="email" name="email_admin" class="form-control" value="<?= htmlspecialchars($data['email_admin']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>No Telfon</label>
                                <input type="number" name="no_telp" class="form-control" value="<?= htmlspecialchars($data['no_telp']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat Admin</label>
                                <textarea name="alamat_admin" class="form-control"required><?= htmlspecialchars($data['alamat_admin']) ?></textarea>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-double-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../index.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <?= include('../../tamplates/script.php'); ?>

</body>

</html>