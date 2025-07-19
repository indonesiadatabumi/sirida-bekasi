<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");	
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_system_params',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();	
	
	$arr_field = array('value');

	foreach($_POST as $key => $val)
	{
		if(in_array($key,$arr_field))
		{				
			$arr_data[$key]=$global->real_escape_string($val);
		}
	}
	
	$id=$_POST['id'];
	$cond = "id='".$id."'";
	$result = $DML->update($arr_data,$cond);
	
	if(!$result)
		die('failed');		
	
	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>