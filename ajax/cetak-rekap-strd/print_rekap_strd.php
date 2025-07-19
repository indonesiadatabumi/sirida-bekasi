<?php
require_once("inc/init.php");
require_once "function.php";

$jenis_retribusi = $_POST['jenis_retribusi'];
$periode_awal = $_POST['periode_awal'];
$periode_akhir = $_POST['periode_akhir'];
$periode = format_periode($periode_awal, $periode_akhir);

$sql = "SELECT a.strd_id, a.strd_periode, a.strd_pajak, b.npwrd, b.wp_wr_nama, b.nm_rekening, b.bln_retribusi
	FROM app_strd a 
	LEFT JOIN app_skrd b ON a.strd_skrd_id=b.id_skrd
	WHERE a.strd_jenis_retribusi = '$jenis_retribusi' AND strd_tgl_proses >= '$periode_awal' AND strd_tgl_proses <= '$periode_akhir'";

$result = $db->GetAll($sql);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap STRD Cetak</title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">

</head>

<body>
    <div style="margin:10px;">
        <h3 align="center">Rekap Daftar STRD<br />
            <span style="font-weight:normal">PERIODE <?= $periode ?></span>
        </h3>
        <br />
        <table class='report' cellpadding='0' cellspacing='0' width="100%">
            <thead>
                <tr role="row">
                    <th>No</th>
                    <th>NPWRD</th>
                    <th>Nama WR</th>
                    <th>Jenis Retribusi</th>
                    <th>Bulan Retribusi</th>
                    <th>Periode</th>
                    <th>Jumlah Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($result as $row) : ?>
                    <?php $bln_retribusi = nama_bulan($row['bln_retribusi']); ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['npwrd'] ?></td>
                        <td><?= $row['wp_wr_nama'] ?></td>
                        <td><?= $row['nm_rekening'] ?></td>
                        <td><?= $bln_retribusi ?></td>
                        <td><?= $row['strd_periode'] ?></td>
                        <td align="right"><?= number_format($row['strd_pajak'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <footer style="margin-top:10px;">
            Printed on <?= date('d-m-Y ') . " from " . $_SITE_TITLE . " " . $_ORGANIZATION_ACR . " " . $_CITY; ?>
        </footer>
    </div>
</body>

</html>