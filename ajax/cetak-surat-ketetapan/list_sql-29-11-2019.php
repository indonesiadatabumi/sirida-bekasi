<?php		

	$list_sql = "SELECT a.id_skrd,a.kd_rekening,a.nm_rekening,a.no_skrd,a.bln_retribusi,a.thn_retribusi,
				(CASE a.tipe_retribusi 
				 WHEN '1' THEN (SELECT total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
				 ELSE (SELECT total_retribusi FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd))
				 END) as total_retribusi,b.dasar_pengenaan
				FROM app_skrd as a LEFT JOIN (SELECT fk_skrd,dasar_pengenaan FROM app_nota_perhitungan) b ON (a.id_skrd=b.fk_skrd)";
?>
