<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");	
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_ref_jenis_retribusi',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];
	$item = (isset($_POST['item'])?'1':'0');
	$karcis = (isset($_POST['karcis'])?'1':'0');
	$non_karcis = (isset($_POST['non_karcis'])?'1':'0');

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
	
		$arr_field = array('id_jenis_retribusi','kd_rekening','jenis_retribusi');

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
		$arr_data['item'] = $item;
		$arr_data['karcis'] = $karcis;
		$arr_data['non_karcis'] = $non_karcis;
		
		$n_regulation_row = $_POST['n_regulation_row'];
		$dasar_hukum_pengenaan = "";
		$s = false;
		for($i=1;$i<=$n_regulation_row;$i++){
			if(isset($_POST['dasar_hukum_pengenaan'.$i]))
			{
				$dasar_hukum_pengenaan .= ($s?"|%|":"").$_POST['dasar_hukum_pengenaan'.$i];
				$s = true;
			}
		}

		$arr_data['denda'] = '0';
		$arr_data['dasar_hukum_pengenaan'] = $dasar_hukum_pengenaan;

		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];
		$arr_data['item'] = $item;
		$arr_data['karcis'] = $karcis;
		$arr_data['non_karcis'] = $non_karcis;
		
		$n_regulation_row = $_POST['n_regulation_row'];
		$dasar_hukum_pengenaan = "";
		$s = false;
		for($i=1;$i<=$n_regulation_row;$i++){
			if(isset($_POST['dasar_hukum_pengenaan'.$i]))
			{
				$dasar_hukum_pengenaan .= ($s?"|%|":"").$_POST['dasar_hukum_pengenaan'.$i];
				$s = true;
			}
		}

		$arr_data['dasar_hukum_pengenaan'] = $dasar_hukum_pengenaan;

		$cond = "id_jenis_retribusi='".$id."'";
		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "id_jenis_retribusi='".$id."'";
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