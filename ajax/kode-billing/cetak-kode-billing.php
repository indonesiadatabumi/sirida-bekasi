<?php

require_once("inc/init.php");
require_once("../../helpers/date_helper.php");

$id_skrd = $_GET['id'];

$sql = "SELECT a.npwrd,a.wp_wr_nama,a.wp_wr_alamat,a.bln_retribusi,a.thn_retribusi,
			(CASE a.tipe_retribusi WHEN '1' 
			 THEN (SELECT x.total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
			 ELSE (SELECT x.total_retribusi FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd))
			 END) as total_retribusi,
			a.nm_rekening,a.kd_billing FROM app_skrd as a WHERE(a.id_skrd='" . $id_skrd . "')";



$result = $db->Execute($sql);
$n_row1 = $result->RecordCount();

if ($n_row1 > 0) {
	$row1 = $result->FetchRow();
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?php echo $_SITE_TITLE; ?> | Kode Billing Retribusi</title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">
	<style type="text/css">
		.border {
			border: 1px solid #000;
		}

		table {
			width: 100%;
		}

		table td {
			padding: 5px;
		}

		h3,
		h4 {
			padding: 10px !important;
		}
	</style>
</head>

<body onload="window.print()">
	<?php
	if ($n_row1 > 0) {
	?>
		<div style="width: 750px; margin-left: 10px; margin-top: 15px; ">
			<table border="0" cellspacing="0" style="border: 1px solid; font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; ">
				<tr>
					<td width="70" align="right">
						<img src="<?= ASSETS_URL ?>/img/logo_pemkot_bekasi.png" width="60">
					</td>
					<td style="text-align: center;font-weight: bold;">
						<div style="font-size: 16px;">PEMERINTAH KOTA BEKASI </div>
						<div style="font-size: 13px;">BADAN PENDAPATAN DAERAH </div>
						<div style="font-size: 12px;">
							Jl. Ir.H.Juanda No. 100 <br />
							Telp. (021) 88397963/ 64 Fax. (021) 88397965
						</div>
					</td>
					<td width="75"><img src="<?= ASSETS_URL ?>/img/bank-bjb-logo.png" width="70"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid; border-bottom: 1px solid; font-size: 10px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" colspan="3">SISTEM INFORMASI RETRIBUSI DAERAH KOTA BEKASI</td>
				</tr>
				<tr>
					<td colspan="3">
						<table border="0" width="100%" style="font-size: 12px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
							<tr>
								<td width="20%">NPWPRD</td>
								<td width="2%">:</td>
								<td><?= $row1['npwrd'] ?></td>
							</tr>
							<tr>
								<td>NAMA WR</td>
								<td>:</td>
								<td><?= $row1['wp_wr_nama'] ?></td>
							</tr>
							<tr>
								<td>ALAMAT WR</td>
								<td>:</td>
								<td><?= $row1['wp_wr_alamat'] ?></td>
							</tr>
							<tr>
								<td>MASA RETRIBUSI</td>
								<td>:</td>
								<td><?= get_monthName($row1['bln_retribusi']) ?></td>
							</tr>
							<tr>
								<td>TAHUN RETRIBUSI</td>
								<td>:</td>
								<td><?= $row1['thn_retribusi'] ?></td>
							</tr>
							<tr>
								<td>JENIS RETRIBUSI</td>
								<td>:</td>
								<td><?= $row1['nm_rekening'] ?></td>
							</tr>
							<tr>
								<td>TOTAL RETRIBUSI</td>
								<td>:</td>
								<td>Rp <?= number_format($row1['total_retribusi']) ?></td>
							</tr>
							<tr>
								<td>KODE BILLING</td>
								<td>:</td>
								<td style="font-size: 16px; font-weight:bold;"><?= $row1['kd_billing'] ?></td>
							</tr>
							<tr>
								<td style="border-top: 1px solid; font-size: 10px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" colspan="3">Penyetoran dilakukan melalui Bank Pembagunan Jawa Barat Banten (Bank BJB), dengan Nomor Rekening 3275123456790</td>
															
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div style="width: 750px; margin-left: 10px; margin-top: 15px; text-align: center; font-weight: bold; font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 15px; ">
			" RETRIBUSI ANDA MEMBANGUN KOTA BEKASI "<br />
			-TERIMA KASIH-
		</div>
	<?php } ?>

</body>

</html>