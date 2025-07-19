<?php
	$list_sql = "SELECT a.npwrd,a.nm_wp_wr,a.alamat_wp_wr,a.no_tlp,a.kelurahan,a.kecamatan,a.kota,b.jenis_retribusi FROM public.app_reg_wr as a 
				LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_rekening=b.kd_rekening)
				WHERE(a.tipe_retribusi='1' and a.tgl_pendaftaran between '2020-01-01' and '2026-12-31') ORDER BY a.tgl_pendaftaran DESC limit 500";
?>