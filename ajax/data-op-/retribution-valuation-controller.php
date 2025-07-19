<?php
	$act = $_POST['act'];
	$kd_rekening = $_POST['kd_rekening'];
	$korek_imb = $_POST['korek_imb'];
	$x = explode(' ',$_POST['dasar_pengenaan']);
	$thn_dasar_pengenaan = end($x);

	include_once "retribution-valuation-panel2.php";
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker(
		{ 
			dateFormat: 'dd-mm-yy',
			prevText: '<i class="fa fa-chevron-left"></i>',
	    	nextText: '<i class="fa fa-chevron-right"></i>',
		});
		
		$("#tgl_skrd").mask('99-99-9999');
		$(".thousand_format1").inputmask({
			'alias': 'numeric',
		    rightAlign: true,
		    'groupSeparator': '.',
		    'autoGroup': true
		  });
		$(".thousand_format2").inputmask({
		    'alias': 'decimal',
		    rightAlign: true,
		    'groupSeparator': '.',
		    'autoGroup': true
		  });
		$(".decimal").inputmask({
		    'alias': 'decimal',
		    rightAlign: true
		  });
		$(".year").inputmask({
		    'alias': 'numeric',		    
		    'mask':'9999',
		    rightAlign: false
		  });
	});
</script>