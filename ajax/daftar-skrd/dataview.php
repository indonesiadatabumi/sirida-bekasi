<?php
	$fn = $_POST['fn'];
	$kd_rekening = $_POST['kd_rekening'];
	$src_status = $_POST['src_status'];
	$src_tipe = $_POST['src_tipe'];
	$src_tgl_skrd_awal = $_POST['src_tgl_skrd_awal'];	
	$src_tgl_skrd_akhir = $_POST['src_tgl_skrd_akhir'];	

	$tipe_laporan = $_POST['tipe_laporan'];	
?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	
	var fn = "<?=$fn;?>",tip_l = "<?=$tipe_laporan;?>",kd_rek = "<?=$kd_rekening;?>", sts = "<?=$src_status;?>", type = "<?=$src_tipe;?>", dt1 = "<?=$src_tgl_skrd_awal;?>", dt2 = "<?=$src_tgl_skrd_akhir;?>";

	switch(tip_l)
	{
		case '1':filename='daftar-skrd.php';break;
		case '2':filename='daftar-skrd-pdf.php';break;
		case '3':filename='daftar-skrd-excel.php';break;
	}

	window.open('ajax/'+fn+'/'+filename+'?kd_rek='+kd_rek+'&sts='+sts+'&dt1='+dt1+'&dt2='+dt2+'&type='+type, '_blank');

</script>