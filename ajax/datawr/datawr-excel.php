<?php
require_once("inc/init.php");
// require_once("list_sql.php");
// require_once("../../helpers/date_helper.php");
$kecamatan = strtoupper($_GET['kec']);
$katakunci = strtoupper($_GET['key']);
$retribusi = $_GET['ret'];

if ($kecamatan) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.kecamatan='$kecamatan'";
}

if ($retribusi) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.kd_rekening='$retribusi'";
}

if ($katakunci) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.nm_wp_wr like '%$katakunci%'";
}

$list_sql = "SELECT a.npwrd,a.nm_wp_wr,a.alamat_wp_wr,a.no_tlp,a.kelurahan,a.kecamatan,a.kota,b.jenis_retribusi FROM public.app_reg_wr as a 
			LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_rekening=b.kd_rekening)
            $cond 
			ORDER BY a.npwrd ";

$list_of_data = $db->Execute($list_sql);
if (!$list_of_data)
    print $db->ErrorMsg();

header("Content-Type: application/force-download");
header("Cache-Control: no-cache, must-revalidate");
header("content-disposition: attachment;filename=data-wajib-retribusi.xls");

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $_SITE_TITLE; ?> - Data Wajib Retribusi</title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">

</head>

<body>
    <div style="margin-bottom: 10px; text-align: center;">
        <h3>DATA WAJIB RETRIBUSI </h3>
    </div>
    <table border="1">
        <thead>
            <tr style="font-size: 14px;">
                <th>No.</th>
                <th>NPWRD</th>
                <th>Wajib Retribusi</th>
                <th>Alamat WR</th>
                <th>Kecamatan</th>
                <th>Jenis Retribusi</th>
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

            ?>
                <tr>
                    <td style="vertical-align: middle; text-align: center;"><?= $no ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= $npwrd ?></td>
                    <td><?= $nm_wp_wr ?></td>
                    <td><?= $alamat_wp_wr ?> <?= $kelurahan ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?= $kecamatan ?></td>
                    <td><?= $jenis_retribusi ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</body>

</html>