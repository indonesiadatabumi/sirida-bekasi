<?php
	require_once("../../helpers/date_helper.php");

	$fn = $_POST['fn'];
	$id_berita_acara = $_POST['id_berita_acara'];
	$tipe_laporan = $_POST['tipe_laporan'];	

?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	
	var fn = "<?=$fn;?>", id_berita_acara = "<?=$id_berita_acara;?>", tip_l = "<?=$tipe_laporan;?>";

	switch(tip_l)
	{
		case '1':filename='cetak-ba-serah-terima-benda-berharga.php';break;
		case '2':filename='ba-serah-terima-benda-berharga-pdf.php';break;
	}

	window.open('ajax/'+fn+'/'+filename+'?id='+id_berita_acara, '_blank');

</script>
