<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");	
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_persediaan_benda_berharga',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];
	$fk_permohonan = $_POST['fk_permohonan'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('fk_permohonan','no_persediaan','tgl_persediaan','keterangan','blok_keluar','blok_masuk','no_awal',
    						'no_akhir','sisa_blok','jumlah_lembar','nilai_uang','jenis_transmisi');
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{				
				if($key=='tgl_persediaan')
					$val = us_date_format($val);
				else if($key!='keterangan')
					$val = str_replace(",","",$val);
				else
					$val = $global->real_escape_string($val);

				$arr_data[$key]=$val;
			}
		}
	}

	if($act=='add')
	{
		$arr_data['blok_masuk'] = (isset($arr_data['blok_masuk'])?$arr_data['blok_masuk']:0);
		$arr_data['blok_keluar'] = (isset($arr_data['blok_keluar'])?$arr_data['blok_keluar']:0);

		$arr_data['id_persediaan'] = $global->get_incrementID('app_persediaan_benda_berharga','id_persediaan');
		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id = $_POST['id_persediaan'];
		$no_persediaan = $_POST['no_persediaan'];
		$sisa_blok = $_POST['sisa_blok'];
		$isi_per_blok = $_POST['isi_per_blok'];
		$nilai_per_lembar = $_POST['nilai_per_lembar'];

		$jenis_transmisi = $_POST['jenis_transmisi'];
		if($jenis_transmisi=='1'){
			$no_akhir_masuk_sebelumnya = str_replace(',','',$_POST['no_akhir']);
		}else{
			$no_akhir_masuk_sebelumnya = $db->getOne("SELECT no_akhir FROM app_persediaan_benda_berharga WHERE fk_permohonan='".$fk_permohonan."' 
													  AND jenis_transmisi='1' AND no_persediaan<".$no_persediaan." ORDER BY no_persediaan DESC");
		}

		if($jenis_transmisi=='2'){
			$no_akhir_keluar_sebelumnya = str_replace(',','',$_POST['no_akhir']);
		}else{
			$no_akhir_keluar_sebelumnya = $db->getOne("SELECT no_akhir FROM app_persediaan_benda_berharga WHERE fk_permohonan='".$fk_permohonan."' 
													   AND jenis_transmisi='2' AND no_persediaan<".$no_persediaan." ORDER BY no_persediaan DESC");
		}		

		$cond = "id_persediaan='".$id."'";
		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');

		$sql = "SELECT id_persediaan,blok_keluar,blok_masuk,jenis_transmisi FROM app_persediaan_benda_berharga 
				WHERE fk_permohonan='".$fk_permohonan."' AND no_persediaan>".$no_persediaan." ORDER BY no_persediaan ASC";

		
		$list_of_data = $db->Execute($sql);

		$no=0;
		while($row = $list_of_data->FetchRow())
		{
			$no++;
			$_jenis_transmisi = $row['jenis_transmisi'];
			$blok_keluar = $row['blok_keluar'];
			$blok_masuk = $row['blok_masuk'];
			$id_persediaan = $row['id_persediaan'];

			if($_jenis_transmisi=='1')
			{
				$no_awal = $no_akhir_masuk_sebelumnya + 1;
				$no_akhir = $no_akhir_masuk_sebelumnya + ($row['blok_masuk'] * $isi_per_blok);
				$sisa_blok += $blok_masuk;

				$no_akhir_masuk_sebelumnya = $no_akhir;
			}else{
				$no_awal = $no_akhir_keluar_sebelumnya + 1;
				$no_akhir = $no_akhir_keluar_sebelumnya + ($row['blok_keluar'] * $isi_per_blok);
				$sisa_blok -= $blok_keluar;

				$no_akhir_keluar_sebelumnya = $no_akhir;
			}

			$jumlah_lembar = $sisa_blok * $isi_per_blok;
			$nilai_uang = $jumlah_lembar * $nilai_per_lembar;

			

			$arr_data = array('no_awal'=>$no_awal,'no_akhir'=>$no_akhir,'sisa_blok'=>$sisa_blok,'jumlah_lembar'=>$jumlah_lembar,'nilai_uang'=>$nilai_uang);
			$cond = "id_persediaan='".$id_persediaan."'";
			$result = $DML->update($arr_data,$cond);

			if(!$result)
				die('failed');
		}
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "id_persediaan='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');		
	}	    

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);
    
    //fetching data to generate list of data
    $list_sql .= " WHERE (fk_permohonan='".$fk_permohonan."') ORDER BY no_persediaan ASC";

    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>