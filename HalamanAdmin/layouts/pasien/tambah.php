<?php
// Aktifkan error reporting untuk debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('../../../koneksi.php');

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $nama     = $_POST['nama_pasien'];
    $email    = $_POST['email_pasien'];
    $telp     = $_POST['no_telp'];
    $alamat   = $_POST['alamat_pasien'];

    // Debug jika perlu
    // echo '<pre>'; print_r($_POST); echo '</pre>'; die();

    // Simpan data ke database
    $stmt = $koneksi->prepare("INSERT INTO tbl_pasien (nama_pasien, email_pasien, no_telp, alamat_pasien) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $telp, $alamat);
    $stmt->execute();
    $stmt->close();

    // Redirect
    header('Location: index.php');
    exit;
}
?>

<?php include('../../tamplates/head.php'); ?>

<body id="page-top" style="overflow:hidden">
    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>

                    <!-- Form Input -->
                    <div class="card-body mb-5 pb-5">
                        <h2>Tambah Pasien</h2>
                        <form method="POST" class="mt-4">
                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_pasien" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email_pasien" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nomor Telepon</label>
                                <input type="text" name="no_telp" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat_pasien" class="form-control"></textarea>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
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

    <?= include('../../tamplates/script.php'); ?>
</body>

</html>
