<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../helpers/mix_helper.php");

	$DML1 = new DML('app_pembayaran_retribusi',$db);
//	$DML2 = new DML('pembayaran_sptpd',$db);
	$DML3 = new DML('app_skrd',$db);
	$DML4 = new DML('app_pengembalian_karcis',$db);

	$global = new global_obj($db);	
	
//	$DML2->set_shceme('payment');

	// $ntpd = $_GET['ntpd'];
	// $tipe_retribusi = $_GET['tipe_retribusi'];
	// $fn = $_GET['fn'];
	// $kd_billing = $_GET['kd_billing'];
	// $kd_billing_sc = $_GET['kd_billing_sc'];

	// $ntpd = $_POST['ntpd'];
	$tipe_retribusi = $_POST['tipe_retribusi'];
	$fn = $_POST['fn'];
	$kd_billing = $_POST['kd_billing'];
	$kd_billing_sc = $_POST['kd_billing_sc'];

	$db->BeginTrans();
/*		
	if($tipe_retribusi=='2')
	{			
		$cond = "ntpd='".$ntpd."'";
		$arr_data = array();
		$arr_data['status_bayar'] = '0';
		$arr_data['ntpd'] = '';
		$result = $DML4->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}
	}

	$cond = "ntpd='".$ntpd."'";
	$result = $DML1->delete($cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	$cond = "ntp='".$ntpd."'";
	$result = $DML2->delete($cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	$sql = "SELECT SUM(total_bayar) FROM app_pembayaran_retribusi WHERE(kd_billing='".$kd_billing."')";
	$total_bayar = $db->getOne($sql);
*/
	$cond = "kd_billing='".$kd_billing."'";
	$result = $DML1->delete($cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	// $arr_data = array();
	// $arr_data['status_lunas'] = '0';
	// $arr_data['status_bayar'] = '0';

	// $cond = "kd_billing='".$kd_billing."'";
	// $result = $DML3->update($arr_data,$cond);
	$sql = "UPDATE app_skrd SET status_bayar='0', status_lunas='0' WHERE kd_billing='$kd_billing_sc'";

	$result = $db->Execute($sql);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	// $arr_data2 = array();
	// $cond2 = "kode_bayar='".$kd_billing."'";
	// $arr_data2['status_bayar'] = 0;
	// $arr_data2['ntpd'] = '';
	$sql = "UPDATE app_pengembalian_karcis SET status_bayar='0', ntpd='' WHERE kode_bayar='$kd_billing_sc'";
	
	$result = $db->Execute($sql);
	
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed1');
	}
		

	$db->CommitTrans();

	$cond = " WHERE(a.kd_billing LIKE '".$kd_billing_sc."%')";

	$list_sql .= $cond;
	
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>