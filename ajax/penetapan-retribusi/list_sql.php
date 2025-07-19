<?php
	$list_sql = "SELECT a.npwrd,a.id_skrd,a.no_skrd,a.bln_retribusi,a.thn_retribusi,
				 a.status_ketetapan,a.status_bayar,a.kd_billing,b.nm_rekening,
				 b.total_retribusi,to_char(a.tgl_penetapan,'dd-mm-yyyy') as tgl_penetapan,
				 (SELECT SUM(x.total_bayar) FROM app_pembayaran_retribusi as x WHERE x.kd_billing=a.kd_billing) as total_bayar
				 FROM app_skrd as a 
				 LEFT JOIN (SELECT fk_skrd,nm_rekening,total_retribusi FROM app_nota_perhitungan) as b 
				 ON (a.id_skrd=b.fk_skrd) 
				 WHERE(a.tipe_retribusi='1')";
?>
