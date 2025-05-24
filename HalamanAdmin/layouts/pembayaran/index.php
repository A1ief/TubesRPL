<?php include('../../../koneksi.php'); ?>

<?php
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        p.id_pembayaran,
        p.tanggal_pembayaran,
        SUM(dp.subtotal_pembayaran) AS total_pembayaran,
        d.nama_lengkap AS nama_dokter,
        ps.nama_pasien,
        a.nama_admin
    FROM tbl_pembayaran p
    JOIN tbl_detail_pembayaran dp ON p.id_pembayaran = dp.id_pembayaran
    JOIN tbl_dokter d ON p.id_dokter = d.id_dokter
    JOIN tbl_pasien ps ON p.id_pasien = ps.id_pasien
    JOIN tbl_admin a ON p.id_admin = a.id_admin
    GROUP BY p.id_pembayaran, p.tanggal_pembayaran, d.nama_lengkap, ps.nama_pasien, a.nama_admin
    LIMIT $limit OFFSET $offset
";


$result = $koneksi->query($query);
$total_rows = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_pembayaran")->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Pembayaran</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.css" rel="stylesheet">
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="page-top" style="overflow:hidden">
    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">PEMBAYARAN</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>
                    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Pembayaran</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Total Bayar</th>
                                    <th>Dokter</th>
                                    <th>Pasien</th>
                                    <th>Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr class="text-center">
                                        <td><?= $row['id_pembayaran'] ?></td>
                                        <td><?= $row['tanggal_pembayaran'] ?></td>
                                        <td>Rp<?= number_format($row['total_pembayaran'], 0, ',', '.') ?></td>
                                        <td><?= $row['nama_dokter'] ?></td>
                                        <td><?= $row['nama_pasien'] ?></td>
                                        <td><?= $row['nama_admin'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['id_pembayaran'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="hapus.php?id=<?= $row['id_pembayaran'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetail<?= $row['id_pembayaran'] ?>">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination justify-content-end mb-5 pb-5">
                            <?php if ($page > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
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

    <!-- Modal Detail Transaksi -->
    <?php
    $result->data_seek(0); // Reset pointer hasil query
    while ($row = $result->fetch_assoc()) :
        $id_pembayaran = $row['id_pembayaran'];

        $detailQuery = "
            SELECT 
                o.nama_obat, o.dosis_obat, o.harga,
                dp.jumlah, dp.subtotal_pembayaran
            FROM tbl_detail_pembayaran dp
            JOIN tbl_obat o ON dp.id_obat = o.id_obat
            WHERE dp.id_pembayaran = $id_pembayaran
        ";
        $detailResult = $koneksi->query($detailQuery);
    ?>
        <div class="modal fade" id="modalDetail<?= $id_pembayaran ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel<?= $id_pembayaran ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalDetailLabel<?= $id_pembayaran ?>">Detail Transaksi - ID <?= $id_pembayaran ?></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Nama Obat</th>
                                    <th>Dosis</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($detail = $detailResult->fetch_assoc()) : ?>
                                    <tr class="text-center">
                                        <td><?= $detail['nama_obat'] ?></td>
                                        <td><?= $detail['dosis_obat'] ?></td>
                                        <td>Rp<?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                        <td><?= $detail['jumlah'] ?></td>
                                        <td>Rp<?= number_format($detail['subtotal_pembayaran'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <?php include('../../tamplates/script.php'); ?>

</body>

</html>