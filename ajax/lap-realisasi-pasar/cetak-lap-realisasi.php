<?php	
	require_once("inc/init.php");	
	require_once("../../helpers/date_helper.php");

	$kd_rekening = $_GET['rek'];
	//$kecamatan = $_GET['kec'];
	$pasar = $_GET['pasar'];
	$tgl1 = us_date_format($_GET['tgl1']);
	$tgl2 = us_date_format($_GET['tgl2']);

	// laporan retribusi semua muncul dan data pasar tidak muncul
	if($kd_rekening=='' and $pasar=='')
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,a.tgl_pembayaran,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd) as b ON (a.kd_billing=b.kd_billing)";
	}
	// laporan retribusi pasar muncul
	else if($kd_rekening=='4120120'){
	$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,a.tgl_pembayaran,b.* FROM payment_retribusi_pasar as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,npwrd,wp_wr_nama,wp_wr_alamat,wp_wr_camat,kd_rekening FROM app_skrd_pasar) as b ON (a.kd_billing=b.kd_billing) ";
	}
	
	
	else 
	{
	//$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.masa_awal,a.tahun_pajak,a.ntp,a.kode_billing,a.sptpd_yg_dibayar,b.* FROM payment.pembayaran_sptpd as a 
	//			LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama,wp_wr_camat,kd_rekening FROM app_skrd) as b ON (a.kode_billing=b.kd_billing) ";
	$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,a.tgl_pembayaran,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama,wp_wr_camat,kd_rekening FROM app_skrd) as b ON (a.kd_billing=b.kd_billing) ";
	
		//$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
	//			INNER JOIN (SELECT x.kd_billing,x.no_skrd,x.wp_wr_nama FROM app_skrd as x WHERE(x.kd_rekening='".$kd_rekening."' and x.wp_wr_camat='".$kecamatan."')) as b ON (a.kd_billing=b.kd_billing)";
	}
	//$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
if($kd_rekening=='4120120'){

	if($pasar==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."' ";
	} else {$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."' AND b.wp_wr_alamat like '%".$pasar."%' ";}
	
}else if($kd_rekening=='' and $pasar==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
	}
else
{
//$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
if($pasar==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."'";
	} else	{$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."' ";}
}

	$list_sql .= $cond;
	// var_dump($list_sql);die;
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();
	
	$sql = "SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='" . $kd_rekening . "')";
	$jenis_retribusi = $db->getOne($sql);
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Laporan Realisasi</title>
    	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">
      	
  	</head>
  	<body>
  		<div style="margin:10px;">
	  		<h3 align="center">REALISASI PENERIMAAN <?= strtoupper($jenis_retribusi) ?><br />
	  			<span style="font-weight:normal">PERIODE <?=mix_2Date($tgl1,$tgl2);?></span>
	  		</h3>
	  		<br />
	  		<?php
	
	  		echo "
	  		<table class='report' cellpadding='0' cellspacing='0'>
	  			<thead>
	  				<tr>
	  					<th>No.</th>
	  					<th>JENIS RETRIBUSI</th>
	  					<th>KODE REKENING</th>
	  					<th>MASA RETRIBUSI</th>
	  					<th>NO. SKRD</th>
						<th>NPWRD</th>
	  					<th>NAMA WR</th>
						<th>ALAMAT</th>
	  					<th>NO. Billing</th>
						<th>Tgl Bayar</th>
	  					<th>PENERIMAAN RETRIBUSI (Rp.)</th>
						
	  				</tr>	  				
	  			</thead>
	  			<tbody>";
  					if($list_of_data->RecordCount()>0)
  					{
	  					$no = 0;
	  					$total_retribusi = 0;
	  					while($row=$list_of_data->FetchRow())
	  					{
	  						foreach($row as $key => $val){
				                  $key=strtolower($key);
				                  $$key=$val;
				              }

	  						$no++;
	  						$bg = ($no%2==0?"even":"odd");
	  						echo "<tr>
	  						<td align='center'>".$no.".</td>
	  						<td>".$nm_rekening."</td>
	  						<td align='center'>".$kd_rekening."</td>
	  						<td align='center'>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
	  						<td align='center'>".$no_skrd."</td>
							<td align='center'>".$npwrd."</td>
	  						<td>".$wp_wr_nama."</td>
							<td>".$wp_wr_alamat."</td>
	  						<td align='center'>".$kd_billing."</td>
								<td align='center'>".$tgl_pembayaran."</td>
	  						<td align='right'>".number_format($total_bayar)."</td>
						
	  						</tr>";
	  						$total_retribusi += $total_bayar;
	  					}
	  					echo "
	  					<tfoot>
	  						<tr>
	  							<td colspan='10' align='right'><b>TOTAL</b></td>
	  							<td align='right'>".number_format($total_retribusi)."</td>
	  						</tr>
	  					</tfoot>";
	  				}
	  				else
	  				{
	  					echo "<tr><td colspan='10' align='center'>Data tidak tersedia !</td></tr>";
	  				}	  				
	  			echo "</tbody>
	  		</table>";
		  	
	  		?>
	  		<footer style="margin-top:10px;">
	  			Printed on <?=date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIZATION_ACR." ".$_CITY;?>
	  		</footer>
	  	</div>
  	</body>
</html>