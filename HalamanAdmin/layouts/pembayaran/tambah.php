<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('../../../koneksi.php');

// Ambil data dropdown
$dokter = $koneksi->query("SELECT id_dokter, nama_lengkap FROM tbl_dokter");
$pasien = $koneksi->query("SELECT id_pasien, nama_pasien FROM tbl_pasien");
$admin = $koneksi->query("SELECT id_admin, nama_admin FROM tbl_admin");
$obat = $koneksi->query("SELECT id_obat, nama_obat, harga FROM tbl_obat");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal_pembayaran'];
    $id_dokter = $_POST['id_dokter'];
    $id_pasien = $_POST['id_pasien'];
    $id_admin = $_POST['id_admin'];

    // Simpan transaksi (total dulu 0)
    $koneksi->query("INSERT INTO tbl_pembayaran (id_dokter, id_pasien, id_admin, tanggal_pembayaran, total_pembayaran)
                    VALUES ('$id_dokter', '$id_pasien', '$id_admin', '$tanggal', 0)");
    $id_pembayaran = $koneksi->insert_id;

    $id_obat_array = $_POST['id_obat'];
    $jumlah_array = $_POST['jumlah'];
    $total = 0;

    for ($i = 0; $i < count($id_obat_array); $i++) {
        $id_obat = intval($id_obat_array[$i]);
        $jumlah = intval($jumlah_array[$i]);

        if ($id_obat > 0 && $jumlah > 0) {
            // Ambil harga obat
            $res = $koneksi->query("SELECT harga FROM tbl_obat WHERE id_obat = $id_obat");
            $data = $res->fetch_assoc();
            $harga = $data['harga'];
            $subtotal = $harga * $jumlah;
            $total += $subtotal;

            // Insert detail pembayaran
            $stmt = $koneksi->prepare("INSERT INTO tbl_detail_pembayaran (id_obat, id_pembayaran, jumlah, subtotal_pembayaran) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $id_obat, $id_pembayaran, $jumlah, $subtotal);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Update total pembayaran
    $koneksi->query("UPDATE tbl_pembayaran SET total_pembayaran = $total WHERE id_pembayaran = $id_pembayaran");

    // Redirect ke halaman index atau daftar pembayaran
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tambah Pembayaran Lengkap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SB Admin 2 CSS -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Tambah Pembayaran (Transaksi + Detail)</h1>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="tanggal_pembayaran">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" name="tanggal_pembayaran" required>
                        </div>

                        <div class="mb-3">
                            <label>Dokter</label>
                            <select name="id_dokter" class="form-control" required>
                                <option value="">-- Pilih Dokter --</option>
                                <?php foreach ($dokter as $d): ?>
                                    <option value="<?= $d['id_dokter'] ?>"><?= $d['nama_lengkap'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Pasien</label>
                            <select name="id_pasien" class="form-control" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php foreach ($pasien as $p): ?>
                                    <option value="<?= $p['id_pasien'] ?>"><?= $p['nama_pasien'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Admin</label>
                            <select name="id_admin" class="form-control" required>
                                <option value="">-- Pilih Admin --</option>
                                <?php foreach ($admin as $a): ?>
                                    <option value="<?= $a['id_admin'] ?>"><?= $a['nama_admin'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr>

                        <h5>Detail Obat</h5>

                        <div id="detailContainer">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <select name="id_obat[]" class="form-control" required>
                                        <option value="">-- Pilih Obat --</option>
                                        <?php foreach ($obat as $o): ?>
                                            <option value="<?= $o['id_obat'] ?>">
                                                <?= $o['nama_obat'] ?> (Rp<?= number_format($o['harga'], 0, ',', '.') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="btnAddDetail">+ Tambah Obat</button>

                        <br>
                        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

            <footer class="sticky-footer bg-white mt-5">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?php include('../../tamplates/script.php'); ?>

    <script>
        // Tambah row detail obat
        document.getElementById('btnAddDetail').addEventListener('click', function() {
            const container = document.getElementById('detailContainer');
            const firstRow = container.querySelector('.row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('select, input').forEach(el => el.value = '');
            container.appendChild(newRow);
            bindRemoveButtons();
        });

        // Bind tombol hapus
        function bindRemoveButtons() {
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.onclick = function() {
                    const container = document.getElementById('detailContainer');
                    if (container.children.length > 1) {
                        this.closest('.row').remove();
                    } else {
                        alert('Minimal harus ada satu obat');
                    }
                }
            });
        }
        bindRemoveButtons();
    </script>
</body>

</html>
