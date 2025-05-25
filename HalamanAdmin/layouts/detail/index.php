<?php
include('../../../koneksi.php');

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "
   SELECT 
        dp.id_detail,
        o.nama_obat,
        dp.id_pembayaran,
        dp.jumlah,
        (dp.jumlah * o.harga) AS subtotal_pembayaran
   FROM tbl_detail_pembayaran dp
   JOIN tbl_obat o ON dp.id_obat = o.id_obat
   ORDER BY o.nama_obat ASC
   LIMIT $limit OFFSET $offset
";


$result = $koneksi->query($query);

$total_query = "
   SELECT SUM(dp.jumlah * o.harga) AS total_semua
   FROM tbl_detail_pembayaran dp
   JOIN tbl_obat o ON dp.id_obat = o.id_obat
";
$total_result = $koneksi->query($total_query)->fetch_assoc();
$total_semua = $total_result['total_semua'];


$total_rows = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_detail_pembayaran")->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

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
                        <h1 class="h3 mb-0 text-gray-800">Detail Pembayaran</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>ID Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = $offset + 1; ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr class="text-center">
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                                            <td><?= htmlspecialchars($row['id_pembayaran']) ?></td>
                                            <td><?= htmlspecialchars($row['jumlah']) ?></td>
                                            <td>Rp <?= number_format($row['subtotal_pembayaran'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot class="table-danger">
                                    <tr class="text-center font-weight-bold">
                                        <td colspan="4">Total Keseluruhan</td>
                                        <td>Rp <?= number_format($total_semua, 0, ',', '.') ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <nav>
                            <ul class="pagination justify-content-end mb-5 pb-5">
                                <?php if ($page > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Previous</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>">Next &raquo;</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>

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

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-double-up"></i></a>

    <?php include('../../tamplates/script.php'); ?>
</body>

</html>