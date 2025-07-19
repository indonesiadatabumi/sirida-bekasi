<?php
	$list_sql = "SELECT a.id_berita_acara,a.no_berita_acara,a.nm_pihak_kesatu,a.nip_pihak_kesatu,a.jbt_pihak_kesatu,
				 a.nm_pihak_kedua,a.nip_pihak_kedua,a.jbt_pihak_kedua,to_char(a.tgl_berita_acara,'dd-mm-yyyy') as tgl_berita_acara,
				 (SELECT COUNT(1) FROM app_dtl_ba_stbb as x WHERE x.fk_berita_acara=a.id_berita_acara) as jml_perforasi
				 FROM public.app_ba_stbb as a ";
?>