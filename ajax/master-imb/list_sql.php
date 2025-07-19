<?php
	$list_sql = "SELECT a.id,a.npwrd,a.nm_wp_wr,a.alamat_wp_wr,a.kelurahan,a.kecamatan,a.kota,a.no_tlp,b.jenis_retribusi FROM public.app_reg_wr_imb2017 as a 
				LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_rekening=b.kd_rekening)
				WHERE(a.tipe_retribusi='1' and a.tgl_pendaftaran between '2019-01-01' and '2019-12-31' and a.status_ketetapan ='0') ORDER BY a.npwrd DESC Limit 1000";
?>