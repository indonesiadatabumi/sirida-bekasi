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
	'format' => 'Folio-L',
	'orientation' => 'L'
]);

$global = new global_obj($db);

$id_skrd = $_GET['id'];
$id_pejda1 = $_GET['mengetahui'];

//----------------- pejda 1
$sql_pejda1 = "SELECT * FROM v_pejabat_daerah where pejda_id='" . $id_pejda1 . "'";
$result_pejda1 = $db->Execute($sql_pejda1);
$row_pejda1 = $result_pejda1->FetchRow();

$sql = "SELECT 
          a.npwrd,a.wp_wr_nama,a.wp_wr_alamat,a.nm_rekening,a.kd_billing,
          a.kd_rekening,a.tipe_retribusi,a.no_skrd,a.bln_retribusi,
          a.thn_retribusi, a.tgl_penetapan, a.no_perjanjian,
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
			if ($thn_dasar_pengenaan >= '2017') {
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
    	<title>" . $_SITE_TITLE . " - Nota Perhitungan Retribusi Daerah</title>
    	<link rel='stylesheet' type='text/css' href='../../css/report-style.css'/>
  	</head>
  	<body>
  		<div style='padding:10px;'>
    		<table style='border:1px solid #000' cellpaddding=0 cellspacing=0 width='100%'>
    			<tr>
    				<td width='37%' style='border-right:1px solid #000;border-bottom:1px solid #000;'>
    					<table border=0 width='100%'>
    						<tr>
    							<td width='20%'><img src='../../img/logo_pemkot_bekasi.jpg' width='72'/></td>
    							<td valign='top'>
    								<h4>PEMERINTAH " . strtoupper($system_params[7] . " " . $system_params[6]) . "<br />
                      " . strtoupper($system_params[2]) . "
                    </h4>
                    <small>" . $system_params[3] . "<br />
                      Telp. " . $system_params[4] . ", Fax. " . $system_params[4] . "
                    </small>
                    <h4 style='margin-top:2px!important;'>" . strtoupper($system_params[6]) . "</h4>
    							</td>
    						</tr>
    					</table>
    				</td>
					<td width='30%' style='border-right:1px solid #000;border-bottom:1px solid #000; ' valign='middle'>
						<table width='100%' cellpadding=2 cellspacing=0>
							<tr>
								<td colspan='2' align='center'><h4>SKRD</h4></td>
							</tr>
							<tr>
								<td colspan='2' align='center' style='font-size: 14px; font-weight: bold;'>(SURAT KETETAPAN RETRIBUSI DAERAH)</td>
							</tr>
							<tr><td colspan='2' height='20'></td></tr>
							<tr>
								<td width='40%' >Masa Retribusi </td>
								<td>: " . get_monthName($row1['bln_retribusi']) . "</td>
							</tr>
							<tr>
								<td>Tahun </td>
								<td>: " . $row1['thn_retribusi'] . "</td>
							</tr>
							
						</table>

					</td>
					<td width='17%' style='border-right:1px solid #000;border-bottom:1px solid #000;' valign='top'>
						<ul>
							<li>Lembaran 1: Putih </li>
							<li>Lembaran 2: Merah </li>
							<li>Lembaran 3: Kuning </li>
							<li>Lembaran 4: Hijau </li>
							<li>Lembaran 5: Biru </li>
							<li>Lembaran 6: Biru </li>
						</ul>
					</td>
    				<td align='center' valign='middle' style='border-bottom:1px solid #000;'>
    					Nomor <br />
    					<h4 style='margin-top:10px!important;font-size:1.6em'>
    						" . sprintf('%02d', $row1['no_skrd']) . "
    					</h4>
    				</td>
    			</tr>
    			<tr>
    				<td colspan='4' style='border-bottom:1px solid #000;'>
    					<table width='100%' style='padding-top: 10px; padding-bottom: 5px; padding-left: 10px; font-size: 12px;'>
    						<tr>
    							<td width='8%'>NAMA</td><td>: " . $row1['wp_wr_nama'] . "</td>
    						</tr>
    						<tr>
    							<td>ALAMAT</td><td>: " . $row1['wp_wr_alamat'] . "</td>
    						</tr>
    					</table>
    				</td>
    			</tr>
    			<tr>
    				<td colspan='4' align='center' style='padding-top: 10px; padding-bottom: 5px; font-weight: bold; border-bottom: 1px solid; font-size: 12px;'>
						DASAR HUKUM PENGENAAN RETRIBUSI : <br />
						".$row1['dasar_pengenaan']."
    				</td>
    			</tr>
				<tr>
					<td colspan='4' style='padding-left: 10px;'>
						<table width='100%' border='0' style='margin-top: 10px; margin-bottom: 5px; font-size: 12px;'>
						<tr>
							<td width='15%'>KODE REKENING </td><td width='35%'>: " . $row1['kd_rekening'] . "</td>
							<td width='15%'>NOMOR BAP</td><td>: </td>
						</tr>
						<tr>
							<td>NPWRD </td><td>: ".$row1['npwrd']."</td>
							<td>TANGGAL BAP </td><td>: </td>
						</tr>
						<tr>
							<td>No. Perjanjian </td><td>: ".$row1['no_perjanjian']."</td>
							<td>TANGGAL BAP </td><td>: </td>
						</tr>
						<tr>
							<td>KODE BILLING</td><td>: ".$row1['kd_billing']."</td>
							
						</tr>
						</table>
	
					</td>
				</tr>
				<tr>
					<td colspan='4' style='border-top: 1px solid; border-bottom: 1px solid; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; font-size: 12px;'>
					<div>
						RETRIBUSI (JENIS IZIN) : " . strtoupper($row1['nm_rekening']) . "
					</div>
					</td>
				</tr>
				<tr>
					<td colspan='4'>
						<table width='100%' style='font-size: 12px;'>
							<tr>
								<td width='66%' style='border-bottom: 1px solid; border-right: 1px solid; text-align: center;'>NOTA HITUNG </td>
								<td width='17%' style='border-bottom: 1px solid; border-right: 1px solid; text-align: center;'>JUMLAH (RP) </td>
								<td width='17%' style='border-bottom: 1px solid; text-align: center;'>KETERANGAN </td>
							</tr>
							<tr>
								<td height='80' valign='top' style='border-bottom: 1px solid; border-right: 1px solid; padding-left: 10px; '> 
									" . $row1['nm_rekening'] . " <br />

								</td>
								<td style='border-bottom: 1px solid; border-right: 1px solid; text-align: right; padding-right: 10px;' valign='top'>
									" . number_format($total_retribusi, 0, ',', '.') . " <br />

								</td>
								<td style='border-bottom: 1px solid; '>" . $row1['keterangan'] . " </td>
							</tr>
							<tr>
								<td height='20' valign='middle' style='border-bottom: 1px solid; border-right: 1px solid; text-align: center; '>JUMLAH KESELURUHAN </td>
								<td style='border-bottom: 1px solid; border-right: 1px solid; text-align: right; padding-right: 10px;'>" . number_format($total_retribusi, 0, ',', '.') . " </td>
								<td style='border-bottom: 1px solid; '></td>
							</tr>
							<tr>
								<td colspan='3' height='10'> </td>
							</tr>							
							<tr>
								<td style='border-bottom: 1px solid; padding-left: 10px; font-size: 12px;' colspan='3'>Dengan Huruf : " . ucwords(NumToWords($total_retribusi)) . " Rupiah </td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan='4' style='padding-top: 10px; padding-left: 10px; font-size: 10px; border-bottom: 1px solid;'>
						<div><b>PERHATIAN</b></div>
						<ul>
							<li>Harap penyetoran dilakukan melalui kas daerah Bank Jabar Banten.</li>
							<li>SKRD ini juga berfungsi sebagai nota hitung dan surat pemberitahuan retribusi daerah (SKRD).</li>
							<li>Tanda bukti setoran tunai berlaku juga sebagai surat setoran retribusi daerah (SKRD).</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td colspan='4' style='font-size: 12px;'>
					<table width='100%'>
						<tr>
							<td>&nbsp;</td>
							<td width='30%' align='center'>
								Bekasi, " . indo_date_format($row1['tgl_penetapan'], 'longDate') . "<br />
								a.n Kepala Badan Pendapatan Daerah <br />
								" . $row_pejda1['ref_japeda_nama'] . "
								<br />
								<br />
								<u>" . $row_pejda1['pejda_nama'] . "</u><br />
								" . $row_pejda1['ref_pangpej_ket'] . "<br />
								NIP. " . $row_pejda1['pejda_nip'] . "
							</td>
						</tr>
					</table>

					</td>
				</tr>
    		</table>

    		</div>
 		</body>
		 </html>";

	$mpdf->SetTitle('Surat Ketetapan Retribusi');
	$mpdf->WriteHTML($html);
	$numb = sprintf('%04s', $row['no_skrd']);
	$mpdf->Output("skrd_" . $numb . ".pdf", "I");
} else {
	echo "<center><font color='red'>Data tidak ditemukan!</font></center>";
}
