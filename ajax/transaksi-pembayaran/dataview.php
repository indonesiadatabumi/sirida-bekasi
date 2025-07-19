<?php		
	require_once("inc/init.php");
	// require_once("list_sql.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../lib/global_obj.php");

	$fn = $_POST['fn'];
	$kd_billing_sc = $_POST['kd_billing'];	
	$status_bayar_sc = $_POST['status_bayar'];		

	// $cond = " WHERE(a.kd_billing LIKE '".$kd_billing_sc."%') AND (a.status_lunas='".$status_bayar_sc."')";
	// $list_sql .= $cond;
	$type = $_POST['type_retribusi'];
	if ($type == '2') {
		$id_pemohonan =  $db->getOne("select fk_permohonan from app_pengembalian_karcis where kode_bayar='" . $kd_billing_sc . "' order by fk_permohonan");
	
		$jml = $db->getOne("SELECT COUNT(1) FROM app_pengembalian_karcis WHERE kode_bayar='" . $kd_billing_sc . "' and fk_permohonan=" . $id_pemohonan);
		if ($jml > 0) {
			$list_sql = "SELECT x.fk_skrd as id_skrd, x.npwrd, x.kd_rekening, x.nm_rekening, y.total_retribusi, y.status_bayar, y.ntpd, y.fk_permohonan, y.kode_bayar as kd_billing, b.nm_wp_wr, a.no_skrd, a.status_lunas,
				NULL AS total_bayar, '2' AS tipe_retribusi 
				FROM public.app_pengembalian_karcis y 
				left join app_permohonan_karcis x on x.id_permohonan=y.fk_permohonan 
				left join app_skrd a on x.fk_skrd=a.id_skrd
				left join app_reg_wr b on b.npwrd=x.npwrd and x.id_permohonan=y.fk_permohonan
				where y.kode_bayar='" . $kd_billing_sc . "' and y.fk_permohonan=" . $id_pemohonan . " ";
		} else {
			$list_sql = "SELECT a.id_skrd,a.kd_billing,a.no_skrd,a.tipe_retribusi,a.status_bayar,a.status_lunas,
					b.nm_wp_wr,a.kd_rekening,a.nm_rekening,
					(CASE WHEN a.tipe_retribusi='1' THEN (SELECT x.total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
					ELSE (SELECT (SELECT SUM(y.total_retribusi) FROM app_pengembalian_karcis as y WHERE(y.fk_permohonan=x.id_permohonan)) as total_retribusi 
							FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd)) END) as total_retribusi,
					(SELECT SUM(total_bayar) FROM app_pembayaran_retribusi as x WHERE(x.kd_billing=a.kd_billing)) as total_bayar
					FROM app_skrd as a
					LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd)";
			$cond = " WHERE(a.kd_billing LIKE '" . $kd_billing_sc . "%') AND (a.status_lunas='" . $status_bayar_sc . "')";
			$list_sql .= $cond;
		}
	} else {
		$list_sql = "SELECT a.id_skrd,a.kd_billing,a.no_skrd,a.tipe_retribusi,a.status_bayar,a.status_lunas,
				b.nm_wp_wr,a.kd_rekening,a.nm_rekening,
				(CASE WHEN a.tipe_retribusi='1' THEN (SELECT x.total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
				ELSE (SELECT (SELECT SUM(y.total_retribusi) FROM app_pengembalian_karcis as y WHERE(y.fk_permohonan=x.id_permohonan)) as total_retribusi 
						FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd)) END) as total_retribusi,
				(SELECT SUM(total_bayar) FROM app_pembayaran_retribusi as x WHERE(x.kd_billing=a.kd_billing)) as total_bayar
				FROM app_skrd as a
				LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd)";
		$cond = " WHERE(a.kd_billing LIKE '" . $kd_billing_sc . "%') AND (a.status_lunas='" . $status_bayar_sc . "')";
		$list_sql .= $cond;
	}	
	
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

    $global = new global_obj($db);
    
    $system_params = $global->get_system_params();
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
			<h2>Daftar Ketetapan Retribusi</h2>			
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
