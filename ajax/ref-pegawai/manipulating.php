<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");	
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_ref_pegawai',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];	

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('nama','nip','pangkat','jabatan');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				$arr_data[$key]=$global->real_escape_string($val);
			}
		}
	}

	if($act=='add')
	{
		$eksternal = (isset($_POST['eksternal'])?'1':'0');
		$arr_data['eksternal'] = $eksternal;		
		$arr_data['kd_instansi'] = ($eksternal=='1'?$_POST['kd_instansi']:'');
		$arr_data['id_pegawai'] = $global->get_incrementID('app_ref_pegawai','id_pegawai');
		
		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];
		$eksternal = (isset($_POST['eksternal'])?'1':'0');
		$arr_data['eksternal'] = $eksternal;
		$arr_data['kd_instansi'] = ($eksternal=='1'?$_POST['kd_instansi']:'');
		
		$cond = "id_pegawai='".$id."'";
		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "id_pegawai='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
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