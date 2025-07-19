<?php
	$list_sql = "SELECT a.id_pegawai,a.nama,a.nip,a.pangkat,a.jabatan,
				 (CASE a.eksternal WHEN '0' THEN (SELECT value FROM app_system_params WHERE(id='2')) 
				  ELSE (SELECT x.nm_instansi FROM app_ref_instansi as x WHERE(x.kd_instansi=a.kd_instansi))
				  END) as instansi 
				 FROM public.app_ref_pegawai as a ORDER BY a.id_pegawai ASC";
?>