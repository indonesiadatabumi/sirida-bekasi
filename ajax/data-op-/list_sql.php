<?php	
	$list_sql = "SELECT a.id_nota,a.fk_skrd,a.npwrd,a.no_nota_perhitungan,a.bln_retribusi,a.thn_retribusi,a.nm_rekening,
				a.dasar_pengenaan,a.kd_rekening,a.imb,a.total_retribusi,
				b.no_skrd,b.total_bayar,b.status_ketetapan,b.status_bayar
				FROM app_nota_perhitungan as a
				INNER JOIN (SELECT x.id_skrd,x.no_skrd,x.status_ketetapan,x.status_bayar,
					(SELECT SUM(y.total_bayar) FROM app_pembayaran_retribusi as y WHERE y.kd_billing=x.kd_billing) as total_bayar FROM app_skrd as x) as b 
				ON (a.fk_skrd=b.id_skrd)";
?>
