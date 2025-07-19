<?php	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('payment_retribusi_pasar',$db);
	$DML2 = new DML('app_skrd_pasar',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];	
	
$sql_idP="select max(id_pembayaran)as last from payment_retribusi_pasar";
$maxidx=$db->Execute($sql_idP);

$maxid = $maxidx->FetchRow();
$id_pembayarans=$maxid['last'];

$sql_idSKRD="select max(no_skrd)as last from app_skrd_pasar";
$maxidSKRDx=$db->Execute($sql_idSKRD);

$maxidSKRD = $maxidSKRDx->FetchRow();
$no_skrds=$maxidSKRD['last'];

$sql_idSKRDz="select max(id_skrd)as last from app_skrd_pasar";
$maxidSKRDzx=$db->Execute($sql_idSKRDz);
		
$maxidSKRDxs = $maxidSKRDzx->FetchRow();
$id_skrds=$maxidSKRDxs['last'];	

	$arr_data=array();
	
	


	
	$id_pembayaran = $id_pembayarans+1;
	$no_skrd = $no_skrds+1;
	$id_skrd = $id_skrds+1;
	

//	$id_pembayaran = $_POST['id_pembayaran'];
//	$no_skrd = $_POST['no_skrd'];
//	$id_skrd = $_POST['id_skrd'];
	$kd_rekening= $_POST['kd_rekening'];
	$nm_rekening= $_POST['nm_rekening'];
	$npwrd= $_POST['npwrd'];
	$wp_wr_nama= $_POST['wp_wr_nama'];
	$wp_wr_alamat= $_POST['wp_wr_alamat'];
	$kota= $_POST['kota'];
	$kecamatan= $_POST['kecamatan'];
	$kelurahan= $_POST['kelurahan'];
//	$kd_billing= $kd_rekening."".$no_skrd;
	$total_retribusi= $_POST['total_retribusi'];
	$pembayaran_ke= $_POST['pembayaran_ke'];
	$denda= $_POST['denda'];
	$total_bayar= $_POST['total_bayar'];
	$tgl_penetapanx= $_POST['tgl_penetapan'];
	$tgl_bayarx= $_POST['tgl_bayar'];
	
	$tgl_pen= substr($tgl_penetapanx,0,2);
	$bln_pen= substr($tgl_penetapanx,3,2);
	$thn_pen= substr($tgl_penetapanx,6,4);
	
	$kd_billings= $no_skrd."".$thn_pen."".$bln_pen."".$tgl_pen;
	$kd_billing=$kd_billings+1;
	
	
	$tgl_penetapan = us_date_format($tgl_penetapanx);
	$tgl_bayar = us_date_format($tgl_bayarx);

	if($act=='add'){

	$sql_pembayaran="INSERT INTO public.payment_retribusi_pasar(id_pembayaran, npwrd, bln_retribusi, thn_retribusi, kd_billing, kd_rekening, nm_rekening, ntpd, pembayaran_ke, total_retribusi, denda, total_bayar, tgl_pembayaran)
	VALUES ('$id_pembayaran','$npwrd','$bln_pen','$thn_pen','$kd_billing','$kd_rekening','Retribusi Pelayanan Pasar','".$id_skrd."".$thn_pen."".$bln_pen."','1','$total_retribusi','0','$total_bayar','$tgl_bayar')";
	
	$db->Execute($sql_pembayaran);
	
	$sql_skrd="INSERT INTO public.app_skrd_pasar(no_skrd, bln_retribusi, thn_retribusi, tipe_retribusi, kd_billing, npwrd, wp_wr_nama, wp_wr_alamat, wp_wr_lurah, wp_wr_camat, wp_wr_kabupaten, kd_rekening, nm_rekening, user_input, tgl_input, tgl_skrd, tgl_penetapan, status_ketetapan, status_bayar, status_lunas, id_skrd)
	VALUES ('$no_skrd', '$bln_pen', '$thn_pen', '1', '$kd_billing', '$npwrd','$wp_wr_nama', '$wp_wr_alamat', '$kelurahan', '$kecamatan', 'BEKASI', '4120120', 'Retribusi Pelayanan Pasar', 'system', '$tgl_penetapan', '$tgl_penetapan','$tgl_penetapan', '1', '1', '1', '$id_skrd')";

	$db->Execute($sql_skrd);
//echo $sql_skrd;
//	die($sql_skrd);
	}
	
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "kd_billing='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');		
			
		$cond2 = "kd_billing='".$id."'";
		$result2 = $DML2->delete($cond2);
		
		if(!$result2)
			die('failed');
	}	    

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>