<?php
include('../../../koneksi.php');

// Proses simpan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $keluhan = $_POST['keluhan'];
    $diagnosis = $_POST['diagnosis'];
    $tgl_rekam = $_POST['tgl_rekam'];

    $query = "INSERT INTO rekammedis (id_pasien, id_dokter, keluhan, diagnosis, tgl_rekam) 
              VALUES ('$id_pasien', '$id_dokter', '$keluhan', '$diagnosis', '$tgl_rekam')";

    if ($koneksi->query($query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menambahkan data: " . $koneksi->error;
    }
}

// Ambil data pasien dan dokter
$pasien = $koneksi->query("SELECT id_pasien, nama_pasien FROM tbl_pasien");
$dokter = $koneksi->query("SELECT id_dokter, nama_lengkap FROM tbl_dokter");
?>

<?php include('../../tamplates/head.php'); ?>

<body id="page-top">
    <div id="wrapper">
        <?php include('../../tamplates/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('../../tamplates/topbar.php'); ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Tambah Rekam Medis</h1>

                    <form method="POST">
                        <div class="form-group">
                            <label>Pasien</label>
                            <select name="id_pasien" class="form-control" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php while ($p = $pasien->fetch_assoc()) : ?>
                                    <option value="<?= $p['id_pasien'] ?>"><?= $p['nama_pasien'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Dokter</label>
                            <select name="id_dokter" class="form-control" required>
                                <option value="">-- Pilih Dokter --</option>
                                <?php while ($d = $dokter->fetch_assoc()) : ?>
                                    <option value="<?= $d['id_dokter'] ?>"><?= $d['nama_lengkap'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Keluhan</label>
                            <textarea name="keluhan" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Diagnosa</label>
                            <textarea name="diagnosis" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Rekam Medis</label>
                            <input type="date" name="tgl_rekam" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../tamplates/script.php'); ?>
</body>

</html>
