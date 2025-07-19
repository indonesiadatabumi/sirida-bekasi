<?php
$dateY=date('Y');
$dateM=date('m');
$dateN=date('Y-m-d');
	$list_sql = "SELECT a.kd_billing,a.npwrd,b.wp_wr_nama,b.wp_wr_alamat,a.kd_rekening,a.nm_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,a.tgl_pembayaran FROM payment_retribusi_pasar as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama,wp_wr_alamat,wp_wr_camat,kd_rekening FROM app_skrd_pasar) as b ON (a.kd_billing=b.kd_billing)
	WHERE   to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '$dateN' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='$dateN' and  b.kd_rekening='4120120'  order by a.tgl_pembayaran desc limit 100
	";
	//and b.wp_wr_camat='BEKASI TIMUR'
	//WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$dateY."-".$dateM."-20' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='$dateN' and  b.kd_rekening='4120120'  order by a.tgl_pembayaran desc
	//WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '2022-08-01' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='2022-08-31' and  b.kd_rekening='4120120' and b.wp_wr_alamat='PASAR WISMA JAYA' order by a.tgl_pembayaran desc
?>