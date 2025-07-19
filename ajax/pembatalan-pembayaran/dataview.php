<?php		
	require_once("inc/init.php");
	// require_once("list_sql.php");
	require_once("../../helpers/date_helper.php");

	$fn = $_POST['fn'];
	
	$kd_billing_sc = $_POST['kd_billing'];
	
	$type = $_POST['type_retribusi'];
	
	if ($type == '1') {
		$list_sql = "SELECT a.kd_billing,a.wp_wr_nama as nm_wp_wr,a.tipe_retribusi,b.ntpd,b.nm_rekening,b.total_retribusi,b.total_bayar,to_char(b.tgl_pembayaran,'dd-mm-yyyy') as tgl_pembayaran
					FROM app_skrd a
					LEFT JOIN app_pembayaran_retribusi b ON a.kd_billing=b.kd_billing
					WHERE a.kd_billing = '".$kd_billing_sc."' AND a.status_bayar='1'";
	}else {
		$list_sql = "SELECT a.kode_bayar as kd_billing,b.ntpd,b.nm_rekening,b.total_retribusi,b.total_bayar,to_char(b.tgl_pembayaran,'dd-mm-yyyy') as tgl_pembayaran, d.wp_wr_nama as nm_wp_wr,d.tipe_retribusi
					FROM app_pengembalian_karcis a
					LEFT JOIN app_pembayaran_retribusi b ON a.kode_bayar=b.kd_billing
					LEFT JOIN app_permohonan_karcis c ON a.fk_permohonan=c.id_permohonan
					LEFT JOIN app_skrd d ON c.fk_skrd=d.id_skrd
					WHERE a.kode_bayar = '".$kd_billing_sc."' AND a.status_bayar='1'";
	}

	// $cond = " WHERE(a.kd_billing LIKE '".$kd_billing_sc."%')";

	// $list_sql .= $cond;	

	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();
?>
<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

	<!-- Widget ID (each widget will need unique ID)-->
	<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
		<!-- widget options:
		usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

		data-widget-colorbutton="false"
		data-widget-editbutton="false"
		data-widget-togglebutton="false"
		data-widget-deletebutton="false"
		data-widget-fullscreenbutton="false"
		data-widget-custombutton="false"
		data-widget-collapsed="true"
		data-widget-sortable="false"

		-->
		
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i> </span>
			<h2>Daftar Pembayaran Retribusi</h2>
		</header>

		<!-- widget div-->
		<div>

			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->						
			</div>
			<!-- end widget edit box -->

			<!-- widget content -->
			<div class="widget-body no-padding" id="list-of-data">
				<?php include_once "list_of_data.php"; ?>
			</div>
			<!-- end widget content -->

		</div>
		<!-- end widget div -->

	</div>
	<!-- end widget -->

</article>
<!-- WIDGET END -->
