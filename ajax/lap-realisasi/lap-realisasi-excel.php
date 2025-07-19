<?php
	require_once("inc/init.php");
	require_once("../../lib/PHPExcel/PHPExcel.php");
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global = new global_obj($db);

	$kd_rekening = $_GET['rek'];
	$tgl1 = us_date_format($_GET['tgl1']);
	$tgl2 = us_date_format($_GET['tgl2']);

	if($kd_rekening=='')
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd) as b ON (a.kd_billing=b.kd_billing)";
	}
	else
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				INNER JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd as x WHERE(kd_rekening='".$kd_rekening."')) as b ON (a.kd_billing=b.kd_billing)";
	}
	
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";

	$list_sql .= $cond;
		

	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

  	$system_params = $global->get_system_params();

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getProperties()->setCreator($_SITE_TITLE.'-'.$_ORGANIZATION_ACR)
			    ->setLastModifiedBy($_SITE_TITLE.'-'.$_ORGANIZATION_ACR)
			    ->setTitle("Laporan Realisasi")
			    ->setSubject("Laporan Realisasi");
	$objPHPExcel->setActiveSheetIndex(0);
	$worksheet = $objPHPExcel->getActiveSheet();
	$worksheet->setTitle('Sheet1');





	$filename = "siprd_lap_realisasi_".date('YmdHis').".xls";
	header ( 'Content-Type: application/vnd.ms-excel' );  		
  	header ( 'Content-Disposition: attachment;filename="'.$filename.'"' ); 
  	header ( 'Cache-Control: max-age=0' );
  	$writer = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
  	$writer->save ( 'php://output' );

?>