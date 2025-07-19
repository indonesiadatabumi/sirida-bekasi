<?php
require_once("../../vendor/autoload.php");

require_once("inc/init.php");
require_once("list_sql.php");
require_once("../../lib/DML.php");
require_once("../../lib/global_obj.php");
require_once("../../helpers/mix_helper.php");
require_once("../../helpers/date_helper.php");

$mpdf = new \Mpdf\Mpdf([
	'tempDir' => 'C:\inetpub\wwwroot\siprd\vendor\mpdf\mpdf\tmp',
	'mode' => 'utf-8',
	'format' => 'Folio-P',
	'orientation' => 'P'
]);

$global = new global_obj($db);

$id_skrd = $_GET['id'];
$id_pejda1 = $_GET['mengetahui'];
$nosurat = $_GET['nosurat'];

//----------------- pejda 1
$sql_pejda1 = "SELECT * FROM v_pejabat_daerah where pejda_id='" . $id_pejda1 . "'";

$result_pejda1 = $db->Execute($sql_pejda1);

$row_pejda1 = $result_pejda1->FetchRow();

$sql = "SELECT 
          a.npwrd,a.wp_wr_nama,a.wp_wr_alamat,a.nm_rekening,a.kd_billing,
          a.kd_rekening,a.tipe_retribusi,a.no_skrd,a.bln_retribusi,
          a.thn_retribusi, a.tgl_penetapan,
          b.keterangan,b.dasar_pengenaan,b.imb,c.korek_denda,c.jenis_denda
          FROM app_skrd as a           
          LEFT JOIN app_nota_perhitungan as b ON (a.id_skrd=b.fk_skrd) 
          LEFT JOIN 
            (SELECT x.kd_rekening,y.korek_denda,y.jenis_denda
               FROM app_ref_jenis_retribusi as x 
               LEFT JOIN 
                (SELECT id_jenis_retribusi,kd_rekening as korek_denda,jenis_retribusi as jenis_denda 
                   FROM app_ref_jenis_retribusi) as y 
                 ON (x.fk_denda=y.id_jenis_retribusi)) as c 
               ON (a.kd_rekening=c.kd_rekening) WHERE(a.id_skrd='" . $id_skrd . "')";

$result = $db->Execute($sql);

if (!$result) {
	die("<center><font color='red'>terjadi kesalahan</font></center>");
}

$n_skrd = $result->RecordCount();

if ($n_skrd > 0) {
	$row1 = $result->FetchRow();

	if ($row1['tipe_retribusi'] == '1') {

		$gr_sub_select = "SELECT total_nilai_imb FROM app_rincian_nota_perhitungan_imb2 as x WHERE(x.fk_nota=a.id_nota)";

		if ($row1['imb'] == '1') {
			$x = explode(' ', $row1['dasar_pengenaan']);
			$thn_dasar_pengenaan = end($x);
			if ($thn_dasar_pengenaan == '2017') {
				$gr_sub_select = "SELECT grand_total_retribusi FROM app_perhitungan_imb2017 as x WHERE(x.fk_nota=a.id_nota)";
			}
		}

		$sql = "SELECT imb,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(ketetapan) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE (" . $gr_sub_select . ")
               END) as ketetapan_retribusi,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(total) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE (" . $gr_sub_select . ")
               END) as total_retribusi,
               (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(kenaikan) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_kenaikan,
               (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(bunga) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_bunga,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(denda) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_denda
               FROM app_nota_perhitungan as a WHERE(a.fk_skrd='" . $id_skrd . "') ;";


		$row2 = $db->getRow($sql);
		$ketetapan_retribusi = $row2['ketetapan_retribusi'];
		$total_kenaikan = $row2['total_kenaikan'];
		$total_bunga = $row2['total_bunga'];
		$total_denda = $row2['total_denda'];
		$total_retribusi = $row2['total_retribusi'];
	} else {
		$sql = "SELECT nilai_total_perforasi,total_retribusi FROM app_permohonan_karcis WHERE(fk_skrd='" . $id_skrd . "')";
		$row2 = $db->getRow($sql);
		$ketetapan_retribusi = $row2['nilai_total_perforasi'];
		$total_retribusi = $row2['total_retribusi'];
		$total_kenaikan = 0;
		$total_bunga = 0;
		$total_denda = 0;
	}

	$system_params = $global->get_system_params();


	$html = "<!DOCTYPE html><html>
  	<head>
    	<meta charset='UTF-8'>
    	<title>".$_SITE_TITLE."</title>
    	<link rel='stylesheet' type='text/css' href='../../css/report-style.css'/>
  	</head>
  	<body>
  		<div style='padding:10px;'>
			<table cellpaddding=0 cellspacing=0 width='98%'>
				<tr>
					<td width='40%' style='border-bottom:1px solid #000;' colspan='3'>
						<table border=0 width='100%'>
						<tr>
							<td width='10%' align='center'><img src='../../img/logo_pemkot_bekasi.jpg' width='72' /></td>
							<td valign='top'>
								<h4>PEMERINTAH " . strtoupper($system_params[7] . " " . $system_params[6]) . " <br />" . strtoupper($system_params[2]) . "</h4>
								<small>" . $system_params[3] . "<br /> Telp " . $system_params[4] . ", Fax " . $system_params[4] . "</small>
								<h4 style='margin-top:2px!important;'>" . strtoupper($system_params[6]) . "</h4>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			";

	$html .= '		
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:10px;" valign="top">
						SKRD : ' . sprintf('%02d', $row1['no_skrd']) . '
					</td>
					<td width="60%">
						Kepada Yth, <br />
						' . $row1['wp_wr_nama'] . ' <br />
						' . $row1['wp_wr_alamat'] . '
					</td>
				</tr>
				<tr>
					<td colspan="3">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
						<h2><u>SURAT TEGURAN</u> </h2>
						Nomor: ' . $nosurat . '
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding:10px;" valign="top">
						Menurut pembukuan kami, hingga saat ini saudara masih mempunyai piutang/ tunggakan Retribusi Daerah sebagai berikut:
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table border="1" cellpadding=2 cellspacing=0 width="100%">
							<thead>
								<tr>
									<th width="30%">Retribusi Daerah</th>
									<th>Tahun</th>
									<th>Nomor dan Tanggal SKRD</th>
									<th>Jumlah Piutang/ Tunggakan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>' . $row1['nm_rekening'] . '</td>
									<td align="center">' . $row1['thn_retribusi'] . '</td>
									<td>No: ' . $row1['no_skrd'] . ' Tgl: ' . $row1['tgl_penetapan'] . '</td>
									<td align="right">Rp ' . number_format($total_retribusi, 0, ',', '.') . '</td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td align="right">Rp ' . number_format($total_retribusi, 0, ',', '.') . '</td>
								</tr>
							</tbody>
						</table>

					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding-left: 20px;">Dengan Huruf : ' . ucwords(NumToWords($total_retribusi)) . ' Rupiah </td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">
						Untuk mencegah tindakan penagihan dengan surat penagihan berdasarkan peraturan daerah kota bekasi Nomor ' . $row1['dasar_pengenaan'] . ' Tentang Penyelenggaraan
						' . $row1['nm_rekening'] . ', maka diminta kepada saudara agar melunasi jumlah piutang/ tunggakan dalam waktu 7 (tujuh) hari setelah tanggal surat teguran ini.
						<br /><br />
						Dalam hal saudara telah melunasi piutang/ tunggakan tersebut diatas, surat teguran ini dapat diabaikan dan diminta agar saudara segera melaporkan bukti pembayaran
						kepada kami (Bidang Pengawasan dan Pengendalian Pajak Daerah pada Badan Pendapatan Daerah KOta Bekasi).
					</td>
				</tr>
    		</table>
			';

	$html .= '
			<table style="margin-top: 30px;" border="0" width="98%">
				<tr>
					<td rowspan="4" style="border-left: 1px solid; border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px 5px 5px 5px;">
						<div>
							<h4 style="text-align: center;">CATATAN:</h4>
							<div>
								Apabila sampai dengan batas waktu 7 (tujuh) hari setelah tanggal surat teguran ini belum melunasi pembayaran, maka akan diterbitkan
								surat panggilan (' . $row1['dasar_pengenaan'] . ') tentang Penagihan atas Pembayaran Retribusi Yang Terlambat.
							</div>
						</div>
					</td>
					<td width="20%"></td>
					<td width="40%" align="center">
						Bekasi, ' . indo_date_format($row1['tgl_penetapan'], 'longDate') . ' <br />
						' . $row_pejda1['ref_japeda_nama'] . '
					</td>
				</tr>
				<tr>
					<td height="40" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td align="center">
						<u>' . $row_pejda1['pejda_nama'] . '</u> <br />
						' . $row_pejda1['pejda_nip'] . '
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
			
    	</div>
	</body>
	</html>';

	$mpdf->SetTitle('Surat Ketetapan Retribusi');
	$mpdf->WriteHTML($html);
	$numb = sprintf('%04s', $row['no_skrd']);
	$mpdf->Output("surat teguran.pdf", "I");
} else {
	echo "<center><font color='red'>Data tidak ditemukan!</font></center>";
}
