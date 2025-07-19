<?php
	$list_sql = "SELECT a.kd_billing,a.ntpd,a.nm_rekening,a.total_retribusi,a.total_bayar,to_char(a.tgl_pembayaran,'dd-mm-yyyy') as tgl_pembayaran,
				b.nm_wp_wr,c.tipe_retribusi FROM payment_retribusi_pasar as a 
				LEFT JOIN (SELECT npwrd,nm_wp_wr FROM app_reg_wr) as b ON (a.npwrd=b.npwrd)
				LEFT JOIN (SELECT tipe_retribusi,kd_billing FROM app_skrd_pasar) as c ON (a.kd_billing=c.kd_billing)";
?>