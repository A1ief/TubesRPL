<?php
include('../../../koneksi.php');

// Set header agar browser mengunduh file sebagai Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_obat.xls");

echo "<table border='1'>";
echo "<tr>
        <th>Id Obat</th>
        <th>Nama Obat</th>
        <th>Dosis Obat</th>
      </tr>";

$query = $koneksi->query("SELECT * FROM tbl_obat");
while ($row = $query->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id_obat']}</td>
            <td>{$row['nama_obat']}</td>
            <td>{$row['dosis_obat']}</td>
          </tr>";
}
echo "</table>";
?>
