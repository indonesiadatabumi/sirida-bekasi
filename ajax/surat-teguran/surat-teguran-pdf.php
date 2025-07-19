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
$id_pejda1 = $_GET['ttd'];
$nosurat = $_GET['nosurat'];
$perihal = $_GET['hal'];

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
    	<title></title>
    	<link rel='stylesheet' type='text/css' href='../../css/report-style.css'/>
  	</head>
  	<body>
  		<div>
			<table cellpaddding=0 cellspacing=0 width='98%' border=0>
				<tr>
					<td width='40%' style='border-bottom:1px solid #000;' colspan='3'>
						<table border=0 width='100%'>
						<tr>
							<td width='15%' align='center'><img src='../../img/logo_pemkot_bekasi.jpg' width='72' /></td>
							<!--
							<td valign='top' align='center'>
								<h4>PEMERINTAH " . strtoupper($system_params[7] . " " . $system_params[6]) . " <br />" . strtoupper($system_params[2]) . "</h4>
								<small>" . $system_params[3] . "<br /> Telp " . $system_params[4] . ", Fax " . $system_params[4] . "</small>
								<h4 style='margin-top:2px!important;'>" . strtoupper($system_params[6]) . "</h4>
							</td>
							-->
							<td valign='top' style='text-align: center;'>
								<div>PEMERINTAH KOTA BEKASI</div>
								<div style='font-size: 28px; font-family: Verdana; font-weight: bold;'>BADAN PENDAPATAN DAERAH </div>
								<div style='font-size: 12px;'>Jl. Ir.H.Juanda No. 100 Telp. (021)88397963, Fax. (021)88397965 </div>
								<div style='font-weight: bold;'>BEKASI</div>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			";

	$html .= '		
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td style="font-size: 12px;">Bekasi, ' . indo_date_format(date('Y-m-d'), 'longDate') . '</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:10px;" valign="top">
						<table border="0" cellpadding="2" cellspacing="0" width="100%" style="font-size: 12px;">
						<tr>
							<td>Nomor </td><td width="80%">: ' . base64_decode($nosurat) . '</td>
						</tr>
						<tr>
							<td>Sifat </td><td>: Segera</td>
						</tr>
						<tr>
							<td>Lampiran </td><td>: -</td>
						</tr>
						<tr>
							<td>Hal </td><td>: ' . $perihal . '</td>
						</tr>
						</table>
					</td>
					<td width="40%" style="font-size: 12px;" valign="top">
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
					<td colspan="3" style="padding-left:90px; text-align: justify;" valign="top">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Berkenaan Peraturan Daerah Kota Bekasi Nomor 04 Tahun 2017 tentang Penyelenggaraan dan Retribusi Izin Mendirikan Bangunan (IMB) dan 
						catatan pembukuan pada Badan Pendapatan Daerah Kota Bekasi, dengan ini disampaikan hal sebagai berikut:
						<br /><br />
						<table width="100%" border=0>
						<tr>
							<td valign="top">1. </td>
							<td>
							Berdasarkan Surat Ketetapan Retribusi Daerah (SKRD) Nomor ' . sprintf('%02d', $row1['no_skrd']) . ' tanggal ' . indo_date_format(date($row1['tgl_penetapan']), 'longDate') . ' terkait 
							' . $row1['nm_rekening'] . ', atas nama ' . $row1['wp_wr_nama'] . '
							belum melakukan pembayaran ' . $row1['nm_rekening'] . ' sebesar Rp ' . number_format($total_retribusi, 0, ',', '.') . ' 
							(' . ucwords(NumToWords($total_retribusi)) . ' Rupiah)
							</td>
						</tr>
						<tr>
							<td valign="top">2. </td>
							<td>
							Apabila sampai dengan batas waktu 7 (tujuh) hari setelah tanggal surat ini belum melakukan pembayaran, maka akan diterbitkan surat panggilan.
							</td>
						</tr>
						<tr>
							<td valign="top">3. </td>
							<td>
							Apabila saudara telah melakukan pembayaran ' . $row1['nm_rekening'] . ' dimaksud, dimohon agar saudara segera melaporkan kepada 
							Bidang Pengawasan dan Pengendalian Pendapatan Daerah pada Badan Pendapatan Daerah Kota Bekasi.
							</td>
						</tr>
						</table>

						<br />
						<div>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Demikian agar menjadi maklum, atas perhatian dan kerja sama yang baik diucapkan terima kasih.
						</div>
					</td>
				</tr>
    		</table>
			';

	$html .= '
			<table style="margin-top: 30px; font-size: 12px;" border="0" width="98%">
				<tr>
					<td rowspan="4">
						&nbsp;
					</td>
					<td width="20%"></td>
					<td width="50%" align="left">
						' . $row_pejda1['ref_japeda_nama'] . '
					</td>
				</tr>
				<tr>
					<td height="40" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td align="left">
						<u>' . $row_pejda1['pejda_nama'] . '</u> <br />
						' . $row_pejda1['ref_pangpej_ket'] . ' <br />
						NIP. ' . $row_pejda1['pejda_nip'] . '
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" style="font-size: 11px;">
						Tembusan:
						<div>
							1. Plt. Inspektur Daerah Kota Bekasi; <br />
							2. Asisten Administrasi Umum dan Perekonomian Setda Kota Bekasi; <br />
							3. Kepala Badan Pengelolaan Keuangan dan Aset Daerah Kota Bekasi.
						</div>
					</td>
			</tr>
			</table>
    	</div>
	</body>
	</html>';

	$mpdf->SetTitle('Surat '.$perihal);
	$mpdf->WriteHTML($html);
	$mpdf->Output("Surat_" . $perihal . ".pdf", "I");
} else {
	echo "<center><font color='red'>Data tidak ditemukan!</font></center>";
}
