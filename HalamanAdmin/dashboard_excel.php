<?php
require '../excel/vendor/autoload.php';
include('../koneksi.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\{
    Chart,
    DataSeries,
    DataSeriesValues,
    Layout,
    Legend,
    PlotArea,
    Title,
    Axis
};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// Ambil data kartu
$data_pasien = $koneksi->query("SELECT COUNT(*) as total_pasien FROM tbl_pasien")->fetch_assoc();
$data_dokter = $koneksi->query("SELECT COUNT(*) as total_dokter FROM tbl_dokter")->fetch_assoc();
$data_pembayaran = $koneksi->query("SELECT SUM(total_pembayaran) as total_pembayaran FROM tbl_pembayaran")->fetch_assoc();
$data_obat = $koneksi->query("SELECT COUNT(*) as total_obat FROM tbl_obat")->fetch_assoc();

// Data grafik
$grafikResult = $koneksi->query("
    SELECT tanggal_pembayaran, SUM(dp.subtotal_pembayaran) AS total
    FROM tbl_pembayaran p
    JOIN tbl_detail_pembayaran dp ON p.id_pembayaran = dp.id_pembayaran
    GROUP BY tanggal_pembayaran
    ORDER BY tanggal_pembayaran ASC
");

$tanggalGrafik = [];
$totalGrafik = [];
while ($row = $grafikResult->fetch_assoc()) {
    $tanggalGrafik[] = $row['tanggal_pembayaran'];
    $totalGrafik[] = is_numeric($row['total']) ? $row['total'] : 0;
}

// Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Report Dashboard');

// Kartu info
$sheet->setCellValue('A1', 'Kategori');
$sheet->setCellValue('B1', 'Jumlah');
$sheet->setCellValue('A2', 'Jumlah Pasien');
$sheet->setCellValue('B2', $data_pasien['total_pasien']);
$sheet->setCellValue('A3', 'Jumlah Dokter');
$sheet->setCellValue('B3', $data_dokter['total_dokter']);
$sheet->setCellValue('A4', 'Total Pembayaran');
$sheet->setCellValue('B4', $data_pembayaran['total_pembayaran']);
$sheet->setCellValue('A5', 'Total Obat');
$sheet->setCellValue('B5', $data_obat['total_obat']);

// Header grafik
$sheet->setCellValue('D1', 'Tanggal Pembayaran');
$sheet->setCellValue('E1', 'Total Pembayaran');

// Isi data grafik
$rowNum = 2;
foreach ($tanggalGrafik as $i => $tgl) {
    $sheet->setCellValue('D' . $rowNum, Date::stringToExcel($tgl));
    $sheet->getStyle('D' . $rowNum)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
    $sheet->setCellValue('E' . $rowNum, $totalGrafik[$i]);
    $rowNum++;
}
$lastDataRow = $rowNum - 1;

// Chart
$dataseriesLabels = [
    new DataSeriesValues('String', "'Report Dashboard'!\$E\$1", null, 1),
];
$xAxisTickValues = [
    new DataSeriesValues('String', "'Report Dashboard'!\$D\$2:\$D\$$lastDataRow", null, ($lastDataRow - 1)),
];
$dataSeriesValues = [
    new DataSeriesValues('Number', "'Report Dashboard'!\$E\$2:\$E\$$lastDataRow", null, ($lastDataRow - 1)),
];

$series = new DataSeries(
    DataSeries::TYPE_LINECHART,
    DataSeries::GROUPING_STANDARD,
    range(0, count($dataSeriesValues) - 1),
    $dataseriesLabels,
    $xAxisTickValues,
    $dataSeriesValues
);

$plotArea = new PlotArea(null, [$series]);
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$title = new Title('Grafik Total Pembayaran per Tanggal');
$xAxisLabel = new Title('Tanggal');
$yAxisLabel = new Title('Total');

$chart = new Chart(
    'chart1',
    $title,
    $legend,
    $plotArea,
    true,
    0,
    $xAxisLabel,
    $yAxisLabel
);

$chart->setTopLeftPosition('G1');
$chart->setBottomRightPosition('R20');
$sheet->addChart($chart);

// ========================
// Tambahan: RAPIKAN OTOMATIS
// ========================

// Auto-size kolom
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Bold header
$sheet->getStyle('A1:B1')->getFont()->setBold(true);
$sheet->getStyle('D1:E1')->getFont()->setBold(true);

// Border tabel
$sheet->getStyle("A1:B5")->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);
$sheet->getStyle("D1:E$lastDataRow")->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

// Format angka sebagai Rupiah
$sheet->getStyle('B4')->getNumberFormat()->setFormatCode('"Rp"#,##0');
$sheet->getStyle("E2:E$lastDataRow")->getNumberFormat()->setFormatCode('"Rp"#,##0');

// Warna latar belakang header
$sheet->getStyle('A1:B1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCE5FF');
$sheet->getStyle('D1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF2CC');

// ========================
// Output File
// ========================
$filename = 'report_all_data.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true);
$writer->save('php://output');
exit;
