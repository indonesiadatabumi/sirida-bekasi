<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once('../../lib/menu_management.php');

	//instantiate objects
    $uc = new user_controller($db);    
	$DML = new DML('app_function_access',$db);
	$global = new global_obj($db);
	$menu_obj = new menu_management($db);

	$uc->check_access();	

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
	
		$arr_field = array('men_id_','usr_type_id');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{				
				if($key=='men_id_')
					$arr_data['men_id'] = $val;
				else
					$arr_data[$key]=$global->real_escape_string($val);
			}
		}
	}

	if($act=='add')
	{
		$func_id = $global->get_incrementID('app_function_access','func_id');

		$arr_data['read_priv'] = (isset($_POST['read_priv'])?'1':'0');
		$arr_data['add_priv'] = (isset($_POST['add_priv'])?'1':'0');
		$arr_data['edit_priv'] = (isset($_POST['edit_priv'])?'1':'0');
		$arr_data['delete_priv'] = (isset($_POST['delete_priv'])?'1':'0');		

		$arr_data['func_id'] = $func_id;
		$arr_data['is_delete'] = '1';
		$arr_data['c_time'] = $_CURR_DATE;
		$arr_data['c_user'] = $_SESSION['username'];

		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];

		$arr_data['read_priv'] = (isset($_POST['read_priv'])?'1':'0');
		$arr_data['add_priv'] = (isset($_POST['add_priv'])?'1':'0');
		$arr_data['edit_priv'] = (isset($_POST['edit_priv'])?'1':'0');
		$arr_data['delete_priv'] = (isset($_POST['delete_priv'])?'1':'0');
		
		$arr_data['m_time'] = $_CURR_DATE;
		$arr_data['m_user'] = $_SESSION['username'];
		$cond = "func_id='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "func_id='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');		
	}	    

	$readAccess = $uc->check_priviledge('read',$men_id);
    $addAccess = $uc->check_priviledge('add',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);
    
    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>