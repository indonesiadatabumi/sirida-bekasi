<?php
require_once("inc/init.php");
require_once("list_sql.php");
require_once("../../helpers/date_helper.php");
$npwrd = $_GET['npwrd'];
$tahun = $_GET['tahun'];
$namawr = $_GET['namawr'];
$retribusi = $_GET['namret'];

$list_sql .= " WHERE (a.npwrd='" . $npwrd . "') AND (a.thn_retribusi='" . $tahun . "')";
$list_sql .= " ORDER BY a.no_nota_perhitungan ASC";
$list_of_data = $db->Execute($list_sql);
if (!$list_of_data)
    print $db->ErrorMsg();

header("Content-Type: application/force-download");
header("Cache-Control: no-cache, must-revalidate");
header("content-disposition: attachment;filename=data-retribusi-" . $npwrd . "-" . $tahun . ".xls");

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $_SITE_TITLE; ?> - Data Retribusi</title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">

</head>

<body>
    <h3 align="center">DATA <?= strtoupper($retribusi) ?><br />
        NAMA: <?= strtoupper($namawr); ?> <br />
        Tahun: <?= $tahun ?><br />
    </h3>
    <br />
    <table border="1">
        <thead>
            <tr style="font-size: 14px;">
                <th>No.</th>
                <th>K. Rekening</th>
                <th>Jenis Retribusi</th>
                <th>No.SKRD/Nota</th>
                <th>Masa Retribusi</th>
                <th>Tot. Retribusi</th>
                <th>Tot. Setor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            while ($row = $list_of_data->FetchRow()) {
                foreach ($row as $key => $val) {
                    $key = strtolower($key);
                    $$key = $val;
                }

                $no++;

                $status = "";
                $color = "";
                if ($status_ketetapan == '0') {
                    $status = "belum ditetapkan";
                    $color = "red";
                } else if ($status_ketetapan == '1' and $status_bayar == '0') {
                    $status = "ditetapkan";
                    $color = "orange";
                } else {
                    $status = "terbayar";
                    $color = "green";
                }
                $text_status = "<font color='" . $color . "'>" . $status . "</font>";
            ?>
                <tr>
                    <td style="vertical-align: middle; text-align: center;"><?= $no ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= $kd_rekening ?></td>
                    <td><?= $nm_rekening ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= $no_skrd ?>/<?= $no_nota_perhitungan ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= get_monthName($bln_retribusi) ?> <?= $tahun ?></td>
                    <td style="vertical-align: middle; text-align: right;"><?= number_format($total_retribusi, 0, ",", ".") ?></td>
                    <td style="vertical-align: middle; text-align: right;"><?= number_format($total_bayar, 0, ",", ".") ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= $text_status ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</body>

</html>