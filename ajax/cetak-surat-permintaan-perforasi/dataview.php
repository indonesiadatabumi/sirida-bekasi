<?php

	$fn = $_POST['fn'];
	$id_skrd = $_POST['id_skrd'];	
	$tipe_laporan = $_POST['tipe_laporan'];	
?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	
	var fn = "<?=$fn;?>", id_skrd = "<?=$id_skrd;?>", tip_l = "<?=$tipe_laporan;?>";

	switch(tip_l)
	{
		case '1':filename='cetak-surat-permintaan-perforasi.php';break;
		case '2':filename='surat-permintaan-perforasi-pdf.php';break;
	}

	window.open('ajax/'+fn+'/'+filename+'?id='+id_skrd, '_blank');

</script>
