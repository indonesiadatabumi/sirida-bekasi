<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
	$uc = new user_controller($db);
	$DML1 = new DML('app_ba_stbb',$db);
	$DML2 = new DML('app_dtl_ba_stbb',$db);

	$global = new global_obj($db);	

	$uc->check_access();
	
	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data1=array();
	$arr_data2=array();

	if($act=='add' || $act=='edit')
	{	
		$no_berita_acara = $_POST['no_berita_acara'];
		$thn_retribusi = date('Y');

		if($act=='add')
		{
			//check no_skrd
			$numb = $db->getOne("SELECT no_berita_acara FROM app_ba_stbb WHERE no_berita_acara='".$no_berita_acara."' AND thn_retribusi='".$thn_retribusi."'");
			if(!is_null($numb))
			{
				die("ERROR:No. SKRD sudah digunakan!");
			}
		}

		$arr_field1 = array('nm_pihak_kesatu','nip_pihak_kesatu','jbt_pihak_kesatu',
							'nm_pihak_kedua','nip_pihak_kedua','jbt_pihak_kedua','tgl_berita_acara',
							'no_surat_permohonan','tgl_surat_permohonan');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field1))
			{
				if($key=='tgl_berita_acara' or $key=='tgl_surat_permohonan')
					$val = us_date_format($val);
				else
					$val = $global->real_escape_string($val);

				$arr_data1[$key]=$val;
			}			
		}
	}

	$db->BeginTrans();

	function input_detail_ba($global,$id_ba,$DML2)
	{
		global $db,$_POST;

		$cond = "fk_berita_acara='".$id_ba."'";
		$result = $DML2->delete($cond);
		if(!$result)
		{
			return false;
		}
		
		$n_dtl_ba_row = $_POST['n_dtl_ba_row'];

		for($i=1;$i<=$n_dtl_ba_row;$i++)
		{

			if(isset($_POST['permintaan_perforasi'.$i]))
			{
				$_arr_data = array();
				$_arr_data['fk_permohonan'] = $_POST['permintaan_perforasi'.$i];
				$_arr_data['fk_berita_acara'] = $id_ba;				
				
				$id_dtl_ba = $global->get_incrementID('app_dtl_ba_stbb','id_dtl_berita_acara');
				$_arr_data['id_dtl_berita_acara'] = $id_dtl_ba;				
				
				$result = $DML2->save($_arr_data);

				if(!$result)
				{
					return false;
				}
			}
		}		
		return true;
	}

	if($act=='add')
	{
		$id_berita_acara = $global->get_incrementID('app_ba_stbb','id_berita_acara');
		$arr_data1['id_berita_acara'] = $id_berita_acara;
		$arr_data1['thn_retribusi'] = date('Y');
		$arr_data1['no_berita_acara'] = $no_berita_acara;

		$result = $DML1->save($arr_data1);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}

		$input_detail_ba = input_detail_ba($global,$id_berita_acara,$DML2);

		if(!$input_detail_ba)
		{
			$db->RollbackTrans();
			die('failed2');
		}

	}
	else if($act=='edit')
	{		
		$id_berita_acara = $_POST['id'];		

		$cond = "id_berita_acara='".$id_berita_acara."'";
		$result = $DML1->update($arr_data1,$cond);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');		
		}

		$input_detail_ba = input_detail_ba($global,$id_berita_acara,$DML2);
		if(!$input_detail_ba)
		{
			$db->RollbackTrans();
			die('failed');
		}
		
	}
	else if($act=='delete')
	{
		$id_berita_acara = $_POST['id'];

		$cond = "id_berita_acara='".$id_berita_acara."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = "fk_berita_acara='".$id_berita_acara."'";
		$result = $DML2->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}		

	}	    
	$db->CommitTrans();

	$cond_type = $_POST['cond_type'];
	$tgl_awal = $_POST['tgl_awal'];
	$tgl_akhir = $_POST['tgl_akhir'];

	if($cond_type=='1')
	{
		$curr_month = date('m');
		$cond = "WHERE EXTRACT(MONTH FROM a.tgl_berita_acara)=".$curr_month;
	}
	else
	{
		$cond = "WHERE a.tgl_berita_acara >= '".$tgl_awal."' AND a.tgl_berita_acara <='".$tgl_akhir."'";
	}	

	$list_sql .= $cond;
	
	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

	include_once "list_of_data.php";
?>