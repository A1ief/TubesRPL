<?php include('../../../koneksi.php');

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM tbl_dokter WHERE id_dokter = $id")->fetch_assoc();

if (isset($_POST['update'])) {
    $nama     = $_POST['nama_lengkap'];
    $keahlian = $_POST['keahlian'];
    $jadwal   = $_POST['jadwal'];
    $kontak   = $_POST['kontak'];

    $koneksi->query("UPDATE tbl_dokter SET 
        nama_lengkap='$nama', 
        keahlian='$keahlian', 
        jadwal_praktik='$jadwal', 
        nomor_kontak='$kontak' 
        WHERE id_dokter=$id");

    header('Location: index.php');
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
                    <h2>Edit Dokter</h2>
                    <form method="POST" class="mt-4">
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Keahlian</label>
                            <input type="text" name="keahlian" value="<?= $data['keahlian'] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Jadwal Praktik</label>
                            <input type="date" name="jadwal" class="form-control" value="<?= $data['jadwal_praktik'] ?>">
                        </div>
                        <div class="mb-3">
                            <label>Nomor Kontak</label>
                            <input type="text" name="kontak" value="<?= $data['nomor_kontak'] ?>" class="form-control">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>


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