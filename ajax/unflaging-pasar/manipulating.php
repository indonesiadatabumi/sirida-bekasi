<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	require_once("../../lib/user_controller.php");

	
	
	  $uc = new user_controller($db);


	$uc->check_access();

	$DML1 = new DML('payment_retribusi_pasar',$db);
	
	$DML3 = new DML('app_skrd_pasar',$db);
	

	$global = new global_obj($db);	
	

	$fn = $_POST['fn'];
	$kd_billing = $_POST['kd_billing'];
	//die($kd_billing);
	
	$sql_pembayaran="DELETE FROM public.payment_retribusi_pasar where kd_billing='".$kd_billing."' ";
	
	$db->Execute($sql_pembayaran);
	
	$sql_skrd="DELETE FROM  public.app_skrd_pasar where kd_billing='".$kd_billing."'";
	$db->Execute($sql_skrd);


	$cond = "kd_billing='".$kd_billing."'";
	$result = $DML1->delete($cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	$cond = "kd_billing='".$kd_billing."'";
	$result = $DML3->delete($cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

		

	$db->CommitTrans();


	include_once "list_of_data.php";
?>