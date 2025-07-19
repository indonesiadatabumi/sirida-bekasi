<?php
require_once("../../vendor/autoload.php");

require_once("inc/init.php");
require_once("../../lib/global_obj.php");

$global = new global_obj($db);

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => 'C:\inetpub\wwwroot\siprd\vendor\mpdf\mpdf\tmp',
    'mode' => 'utf-8',
    'format' => 'Folio-L',
    'orientation' => 'L'
]);

$system_params = $global->get_system_params();

$id_permohonan = $_GET['id'];
$no_awal = $_GET['no_awal'];
$no_akhir = $_GET['no_akhir'];

$sql = "SELECT a.kd_karcis,c.nm_wp_wr,a.nm_rekening 
    		FROM app_permohonan_karcis as a
    		LEFT JOIN app_reg_wr as c ON (a.npwrd=c.npwrd)    		
    		WHERE(a.id_permohonan='" . $id_permohonan . "')";
$row1 = $db->getRow($sql);

$list_sql = "SELECT * FROM app_pengembalian_karcis WHERE fk_permohonan='" . $id_permohonan . "' and no_awal_kembali='".$no_awal."' and no_akhir_kembali='".$no_akhir."' ORDER BY tgl_pengembalian DESC ";
$row = $db->getRow($list_sql);


$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Bayar </title>
</head>

<body>
    <div style="padding: 10px;">
        <table width="100%" border="0">
            <tr>
                <td width="9%">
                    <img src="../../img/logo_pemkot_bekasi.jpg" width="90">
                </td>
                <td>
                    <h3>PEMERINTAH' . strtoupper($system_params[7] . ' ' . $system_params[6]) . ' <br />' . strtoupper($system_params[2]) . ' </h3>
                    ' . $system_params[3] . ' <br />
                    Telp. ' . $system_params[4] . ' <br />
                    ' . $system_params[6] . '
                </td>
            </tr>
            <tr><td colspan="2" height="30" style="border-top: 1px solid;"></td></tr>
            <tr>
                <td colspan="2">

                    <table border="0" width="100%" style="font-size: 20px;">
                        <tr>
                            <td width="20%" valign="top">Nama Instansi </td>
                            <td>: ' . $row1['nm_wp_wr'] . ' </td>
                        </tr>
                        <tr>
                            <td>Jenis Retribusi </td>
                            <td>: ' . $row1['nm_rekening'] . '</td>
                        </tr>
                        <tr>
                            <td>Tgl. Pengembalian </td>
                            <td>: ' . $row['tgl_pengembalian'] . '</td>
                        </tr>
                        <tr>
                            <td>No. Awal - Akhir </td>
                            <td>: ' . number_format($row['no_awal_kembali']) . ' - ' . number_format($row['no_akhir_kembali']) . '</td>
                        </tr>
                        <tr>
                            <td>Jumlah Lembar </td>
                            <td>: ' . $row['jumlah_lembar_kembali'] . ' Lembar</td>
                        </tr>
                        <tr>
                            <td>Total Retribusi </td>
                            <td>: ' . number_format($row['total_retribusi']) . '</td>
                        </tr>
                        <tr>
                            <td>Kode Bayar </td>
                            <td>: ' . $row['kode_bayar'] . '</td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>
</body>

</html>';

$mpdf->SetTitle('Cetak kode bayar');
$mpdf->WriteHTML($html);
$mpdf->Output("kodebayar.pdf", "I");

