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
   LIMIT $limit OFFSET $offset
";

$result = $koneksi->query($query);

$total_rows = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_detail_pembayaran")->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Data Detail Pembayaran</title>
    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet" />
    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.css" rel="stylesheet" />
</head>

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
                                        <th>ID Detail</th>
                                        <th>Nama Obat</th>
                                        <th>ID Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr class="text-center">
                                            <td><?= htmlspecialchars($row['id_detail']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                                            <td><?= htmlspecialchars($row['id_pembayaran']) ?></td>
                                            <td><?= htmlspecialchars($row['jumlah']) ?></td>
                                            <td>Rp <?= number_format($row['subtotal_pembayaran'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
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
