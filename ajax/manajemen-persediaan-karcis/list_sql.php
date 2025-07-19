<?php	
	$list_sql = "SELECT id_persediaan,fk_permohonan,no_persediaan,to_char(tgl_persediaan,'dd-mm-yyyy') as tgl_persediaan,keterangan,blok_keluar,blok_masuk,
				no_awal,no_akhir,sisa_blok,jumlah_lembar,nilai_uang FROM app_persediaan_benda_berharga";
?>
