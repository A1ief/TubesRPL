<?php include('../../../koneksi.php'); ?>
<?php include('../../tamplates/head.php'); ?>

<body id="page-top" style="overflow:hidden">

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include('../../tamplates/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">OBAT</h1>
                        <a href="obat_excel.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="card-body">
                        <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Obat</a>

                        <?php
                        $limit = 5;
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        $result = $koneksi->query("SELECT * FROM tbl_obat LIMIT $limit OFFSET $offset");
                        $total_rows = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_obat ")->fetch_assoc()['total'];
                        $total_pages = ceil($total_rows / $limit);
                        ?>

                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>Id Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Dosis Obat</th>
                                        <th>Harga</th>
                                        <th style="width: 132px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr class="text-center">
                                            <td><?= $row['id_obat'] ?></td>
                                            <td><?= $row['nama_obat'] ?></td>
                                            <td><?= $row['dosis_obat'] ?></td>
                                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <a href="edit.php?id=<?= $row['id_obat'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="hapus.php?id=<?= $row['id_obat'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
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

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-double-up"></i>
    </a>

    <?= include('../../tamplates/script.php'); ?>

</body>

</html>