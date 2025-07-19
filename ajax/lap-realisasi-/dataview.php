<?php	
	require_once("../../helpers/date_helper.php");

	$fn = $_POST['fn'];
	$kd_rekening = $_POST['kd_rekening'];
	$kecamatan = $_POST['kecamatan'];
	$tipe_periode = $_POST['tipe_periode_penerimaan'];
	$tgl_penerimaan_awal =$_POST['tgl_penerimaan_awal'];
	$tgl_penerimaan_akhir =$_POST['tgl_penerimaan_akhir'];
	

	$tipe_laporan = $_POST['tipe_laporan'];
?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	var fn = "<?=$fn;?>", rek = "<?=$kd_rekening;?>",kec = "<?=$kecamatan;?>", tgl1 = "<?=$tgl_penerimaan_awal;?>", tgl2 = "<?=$tgl_penerimaan_akhir;?>", tip_l = "<?=$tipe_laporan;?>";

	switch(tip_l)
	{
		case '1':filename='cetak-lap-realisasi.php';break;
		case '2':filename='lap-realisasi-pdf.php';break;
		case '3':filename='lap-realisasi-excel2.php';break;
	}

	// window.open('ajax/'+fn+'/'+filename+'?tgl1='+tgl1+'&tgl2='+tgl2+'&rek='+rek+'&s_byr='+s_byr, '_blank');	

	window.open('ajax/'+fn+'/'+filename+'?tgl1='+tgl1+'&tgl2='+tgl2+'&rek='+rek+'&kec='+kec, '_blank');	

</script>
