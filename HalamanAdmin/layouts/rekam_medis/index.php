<?php include('../../../koneksi.php'); ?>

<?php
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        rm.id_rekammedis,
        ps.nama_pasien,
        d.nama_lengkap AS nama_dokter,
        rm.keluhan,
        rm.diagnosis,
        rm.tgl_rekam
    FROM rekammedis rm
    JOIN tbl_pasien ps ON rm.id_pasien = ps.id_pasien
    JOIN tbl_dokter d ON rm.id_dokter = d.id_dokter
    GROUP BY 
    rm.id_rekammedis, rm.keluhan, rm.diagnosis, rm.tgl_rekam,
    ps.id_pasien,
    d.id_dokter
    LIMIT $limit OFFSET $offset";

$result = $koneksi->query($query);
$total_rows = $koneksi->query("SELECT COUNT(*) AS total FROM rekammedis")->fetch_assoc()['total'];
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
                        <h1 class="h3 mb-0 text-gray-800">Rekam Medis</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>
                    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah</a>

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Nama Dokter</th>
                                    <th>Keluhan</th>
                                    <th>Diagnosa</th>
                                    <th>Tgl Rekam Medis</th>
                                    <th style="width: 132px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = $offset + 1; ?>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['nama_pasien'] ?></td>
                                        <td><?= $row['nama_dokter'] ?></td>
                                        <td><?= $row['keluhan'] ?></td>
                                        <td><?= $row['diagnosis'] ?></td>
                                        <td><?= $row['tgl_rekam'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $row['id_rekammedis'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="hapus.php?id=<?= $row['id_rekammedis'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
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

    <?php include('../../tamplates/script.php'); ?>

</body>

</html>