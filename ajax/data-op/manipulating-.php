<?php

	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../helpers/mix_helper.php");

	
	$ip = get_ip();
	$sec1 = microtime();
	mt_srand((double)microtime()*1000000);
	$sec2 = mt_rand(1000,9999);
	$session_id = md5($sec2.$sec2);

	
	$username= $_SESSION['username'];
	$nama= $_SESSION['fullname'];
	$type_id=$_SESSION['usr_type_id'];
	$user_type= $_SESSION['usr_type'];
	$user_id= $_SESSION['usr_id'];
	/*

	$time= explode(" ", microtime());
	$last_access= (double) $time[1];
	$ctime = date('Y-m-d H:i:s');
	$challenge = md5($session_id);
	$session_content = "{\"username\":\"".$username."\",\"user_type\":".$user_type."\",\"modul\":\"Pendataan\",\"inquiry_access\":\"Input\"}";
	$session_content_ed = "{\"username\":\"".$username."\",\"user_type\":".$user_type."\",\"modul\":\"Pendaaan\",\"inquiry_access\":\"edit\"}";
	$session_content_del = "{\"username\":\"".$username."\",\"user_type\":".$user_type."\",\"modul\":\"Pendataan\",\"inquiry_access\":\"delete\"}";

*/

	//instantiate objects
	$uc = new user_controller($db);
	$DML1 = new DML('app_skrd',$db);
	$DML2 = new DML('app_nota_perhitungan',$db);

	$uc->check_access();

	$input_imb = $_POST['input_imb'];
	
	if($input_imb=='0')
	{
		$DML3 = new DML('app_rincian_nota_perhitungan',$db);
	}
	else
	{
		$x = explode(' ',$_POST['dasar_pengenaan']);
		$thn_dasar_pengenaan = end($x);

		if($thn_dasar_pengenaan!='2012' and $thn_dasar_pengenaan!='2017')
			return false;

		if($thn_dasar_pengenaan=='2012')
		{
			$DML3 = new DML('app_rincian_nota_perhitungan_imb1',$db);
			$DML4 = new DML('app_rincian_nota_perhitungan_imb2',$db);
			
		}else{

			$DML3 = new DML('app_rincian_bangunan_imb2017',$db);
			$DML4 = new DML('app_rincian_prasarana_imb2017',$db);
			$DML5 = new DML('app_indeks_terintegrasi_imb2017',$db);
			$DML6 = new DML('app_perhitungan_imb2017',$db);
		}
	}

	$global = new global_obj($db);	

	$act = $_POST['act'];
	$npwrd = trim($_POST['npwrd']);
	$thn_retribusi = $_POST['thn_retribusi'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data1=array();
	$arr_data2=array();
	$arr_data3=array();

	
	if($act=='add' || $act=='edit')
	{

		$prefix_imb='';
		$kd_rekening = $_POST['kd_rekening'];
		$no_skrd = $_POST['no_skrd'];
		$thn_retribusi = $_POST['thn_retribusi'];

		if($act=='add')
		{
			//check no_skrd
			$numb = $db->getOne("SELECT no_skrd FROM app_skrd WHERE no_skrd='".$no_skrd."' AND kd_rekening='".$kd_rekening."' AND thn_retribusi='".$thn_retribusi."'");
			if(!is_null($numb))
			{
				die("ERROR:No. SKRD sudah digunakan!");
			}
		}
		

		$arr_field1 = array('bln_retribusi','thn_retribusi','tgl_skrd');
		$arr_field2 = array('no_nota_perhitungan','bln_retribusi','thn_retribusi','dasar_pengenaan','keterangan');		
		$arr_field3 = array();

		$result = $db->Execute("SELECT nm_wp_wr,alamat_wp_wr,kelurahan,kecamatan,kota FROM app_reg_wr WHERE(npwrd='".$npwrd."')");
		$row = $result->FetchRow();

		$wp_wr_nama = $row['nm_wp_wr'];
		$wp_wr_alamat = $row['alamat_wp_wr'];
		$wp_wr_lurah = $row['kelurahan'];
		$wp_wr_camat = $row['kecamatan'];
		$wp_wr_kabupaten = $row['kota'];
		$nm_rekening = $db->getOne("SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')");

		if($input_imb=='1')		
		{
			$prefix_imb = 'imb'.(substr($thn_dasar_pengenaan, 2,2)).'_';	
			if($thn_dasar_pengenaan=='2012')
			{
				$arr_field3 = array($prefix_imb.'koef_permohonan',$prefix_imb.'koef_penatausahaan',$prefix_imb.'koef_plat_nomor',$prefix_imb.'koef_penerbitan_srtif_imb',
									$prefix_imb.'koef_verifikasi_data_tkns',$prefix_imb.'koef_pengukuran',$prefix_imb.'koef_pematokan_gsj_gss',$prefix_imb.'koef_gbr_rencana',
									$prefix_imb.'koef_pengawasan_izin',$prefix_imb.'nilai_permohonan',$prefix_imb.'nilai_penatausahaan',$prefix_imb.'nilai_plat_nomor',
									$prefix_imb.'nilai_penerbitan_srtif_imb',$prefix_imb.'nilai_verifikasi_data_tkns',$prefix_imb.'nilai_pengukuran',$prefix_imb.'nilai_pematokan_gsj_gss',
									$prefix_imb.'nilai_gbr_rencana',$prefix_imb.'nilai_pengawasan_izin',$prefix_imb.'total_nilai_imb',$prefix_imb.'grand_total_imb');
			}
		}		

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field1))
			{
				if($key=='tgl_skrd')
					$val = us_date_format($val);
				else
					$val = $global->real_escape_string($val);

				$arr_data1[$key]=$val;
			}

			if(in_array($key,$arr_field2))
			{
				$arr_data2[$key]=$global->real_escape_string($val);
			}

			if(in_array($key,$arr_field3))
			{
				$_key = substr($key,strlen($prefix_imb),(strlen($key)-strlen($prefix_imb)));
				$arr_data3[$_key]= ($val==''?0:str_replace(",","",$val));
			}
		}
	}

	$db->BeginTrans();

	function input_detail_valuation($input_imb,$global,$id_nota,$arr_DML)
	{
		global $db,$_POST,$prefix_imb,$thn_dasar_pengenaan;

		$cond = "fk_nota='".$id_nota."'";
		$result = $arr_DML[0]->delete($cond);
		if(!$result)
		{
			return false;
		}

		if($input_imb=='0')
		{
			$n_valuation_row1 = $_POST['n_valuation_row1'];

			$dtl_bills_id = array(0=>'0');

			for($i=1;$i<=$n_valuation_row1;$i++)
			{

				if(isset($_POST['id_rincian_nota1'.$i]))
				{

					$set_header = isset($_POST['check_header'.$i]);

					$_arr_data = array();
					$_arr_data['uraian'] = $_POST['uraian'.$i];
					$_arr_data['volume'] = ($set_header||$_POST['volume'.$i]==''?0:str_replace(",","",$_POST['volume'.$i]));
					$_arr_data['tarif'] = ($set_header||$_POST['tarif'.$i]==''?0:str_replace(",","",$_POST['tarif'.$i]));
					$_arr_data['ketetapan'] = ($set_header||$_POST['ketetapan'.$i]==''?0:str_replace(",","",$_POST['ketetapan'.$i]));
					$_arr_data['kenaikan'] = ($set_header||$_POST['kenaikan'.$i]==''?0:str_replace(",","",$_POST['kenaikan'.$i]));
					$_arr_data['denda'] = ($set_header||$_POST['denda'.$i]==''?0:str_replace(",","",$_POST['denda'.$i]));
					$_arr_data['bunga'] = ($set_header||$_POST['bunga'.$i]==''?0:str_replace(",","",$_POST['bunga'.$i]));
					$_arr_data['total'] = ($set_header||$_POST['total'.$i]==''?0:str_replace(",","",$_POST['total'.$i]));
					$_arr_data['header'] = ($set_header?'1':'0');
					$_arr_data['no_urut'] = '';
					$_arr_data['fk_nota'] = $id_nota;
					
					$id_rincian_nota = $global->get_incrementID('app_rincian_nota_perhitungan','id_rincian_nota');
					$dtl_bills_id[$i] = $id_rincian_nota;
					$_arr_data['id_rincian_nota'] = $id_rincian_nota;

					if(isset($dtl_bills_id[$_POST['parent'.$i]]))
						$_arr_data['parent'] = $dtl_bills_id[$_POST['parent'.$i]];
					else
						return false;
					
					$result = $arr_DML[0]->save($_arr_data);

					if(!$result)
					{
						return false;
					}
				}
			}
		}
		else
		{

			if($thn_dasar_pengenaan=='2012')
			{
				$n_valuation_row2_1 = $_POST[$prefix_imb.'n_valuation_row2_1'];
				
				for($i=1;$i<=$n_valuation_row2_1;$i++)
				{
					if(isset($_POST[$prefix_imb.'id_rincian_nota2'.$i]))
					{
						$_arr_data = array();
						$_arr_data['bangunan'] = $_POST[$prefix_imb.'bangunan'.$i];
						$_arr_data['luas'] = str_replace(',','',$_POST[$prefix_imb.'luas'.$i]);
						$_arr_data['nilai_satuan'] = str_replace(',','',$_POST[$prefix_imb.'nilai_satuan'.$i]);
						$_arr_data['biaya_bangunan'] = str_replace(',','',$_POST[$prefix_imb.'biaya_bangunan'.$i]);
						$_arr_data['kj'] = ($_POST[$prefix_imb.'kj'.$i]==''?0:str_replace(',','',$_POST[$prefix_imb.'kj'.$i]));
						$_arr_data['gb'] = ($_POST[$prefix_imb.'gb'.$i]==''?0:str_replace(',','',$_POST[$prefix_imb.'gb'.$i]));
						$_arr_data['lb'] = ($_POST[$prefix_imb.'lb'.$i]==''?0:str_replace(',','',$_POST[$prefix_imb.'lb'.$i]));
						$_arr_data['tb'] = ($_POST[$prefix_imb.'tb'.$i]==''?0:str_replace(',','',$_POST[$prefix_imb.'tb'.$i]));
						$_arr_data['nilai_bangunan'] = str_replace(',','',$_POST[$prefix_imb.'nilai_bangunan'.$i]);
						$_arr_data['fk_nota'] = $id_nota;
						
						$id_rincian_nota1 = $global->get_incrementID('app_rincian_nota_perhitungan_imb1','id_rincian_nota');
						$_arr_data['id_rincian_nota'] = $id_rincian_nota1;
						$result = $arr_DML[0]->save($_arr_data);

						if(!$result)
						{
							return false;
						}
					}
				}
			}else{

				$cond = "fk_nota='".$id_nota."'";
				$result = $arr_DML[1]->delete($cond);
				if(!$result)
				{
					return false;
				}

				$n_valuation_row2_2_1 = $_POST[$prefix_imb.'n_valuation_row2_2_1'];
				$n_valuation_row2_2_2 = $_POST[$prefix_imb.'n_valuation_row2_2_2'];

				//insert data to app_rincian_bangunan_imb2017
				for($i=1;$i<=$n_valuation_row2_2_1;$i++){
					if(isset($_POST[$prefix_imb.'id_rincian_bangunan'.$i])){
						$_arr_data = array();
						$_arr_data['bangunan'] = $_POST[$prefix_imb.'bangunan'.$i];
						$_arr_data['luas'] = str_replace(',','',$_POST[$prefix_imb.'luas_bangunan'.$i]);
						$_arr_data['fk_nota'] = $id_nota;
						
						$id_rincian_bangunan = $global->get_incrementID('app_rincian_bangunan_imb2017','id_rincian_bangunan');
						$_arr_data['id_rincian_bangunan'] = $id_rincian_bangunan;
						$result = $arr_DML[0]->save($_arr_data);

						if(!$result)
						{
							return false;
						}	
					}
				}

				//insert data to app_rincian_prasarana_imb2017
				for($i=1;$i<=$n_valuation_row2_2_2;$i++){
					if(isset($_POST[$prefix_imb.'id_rincian_prasarana'.$i])){
						$_arr_data = array();
						$_arr_data['prasarana'] = $_POST[$prefix_imb.'prasarana'.$i];
						$_arr_data['luas'] = str_replace(',','',$_POST[$prefix_imb.'luas_prasarana'.$i]);
						$_arr_data['satuan'] = str_replace(',','',$_POST[$prefix_imb.'satuan'.$i]);
						$_arr_data['indeks_penggunaan'] = str_replace(',','',$_POST[$prefix_imb.'indeks_penggunaan_prasarana'.$i]);
						$_arr_data['harga_satuan_retribusi'] = str_replace(',','',$_POST[$prefix_imb.'harga_satuan_retribusi_prasarana'.$i]);
						$_arr_data['total_nilai_retribusi'] = str_replace(',','',$_POST[$prefix_imb.'tot_nilai_retribusi_prasarana'.$i]);
						$_arr_data['fk_nota'] = $id_nota;
						
						$id_rincian_prasarana = $global->get_incrementID('app_rincian_prasarana_imb2017','id_rincian_prasarana');
						$_arr_data['id_rincian_prasarana'] = $id_rincian_prasarana;
						$result = $arr_DML[1]->save($_arr_data);

						if(!$result)
						{
							return false;
						}	
					}
				}			

			}
		}
		return true;
	}

	if($act=='add')
	{
		$curr_date = date('Y-m-d H:i:s');

		$id_skrd = $global->get_incrementID('app_skrd','id_skrd');		

		$arr_data1['no_skrd'] = $no_skrd;
		$arr_data1['tipe_retribusi'] = '1';
		$arr_data1['tgl_input'] = $curr_date;
		$arr_data1['user_input'] = $_SESSION['username'];
		$arr_data1['status_ketetapan'] = '0';
		$arr_data1['status_bayar'] = '0';
		$arr_data1['status_lunas'] = '0';
		$arr_data1['npwrd'] = $npwrd;
		$arr_data1['wp_wr_nama'] = $wp_wr_nama;
		$arr_data1['wp_wr_alamat'] = $wp_wr_alamat;
		$arr_data1['wp_wr_lurah'] = $wp_wr_lurah;
		$arr_data1['wp_wr_camat'] = $wp_wr_camat;
		$arr_data1['wp_wr_kabupaten'] = $wp_wr_kabupaten;
		$arr_data1['kd_rekening'] = $kd_rekening;
		$arr_data1['nm_rekening'] = $nm_rekening;
		$arr_data1['id_skrd'] = $id_skrd;

		//app_skrd
		$result = $DML1->save($arr_data1);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}		

		if($input_imb=='0')
		{
			$total_retribusi = str_replace(",","",$_POST['total_perhitungan_nr']);
		}else{
			$total_retribusi = str_replace(",","",$thn_dasar_pengenaan=='2012'?$_POST[$prefix_imb.'grand_total_imb']:$_POST[$prefix_imb.'grand_total_retribusi']);
			$arr_data2['jenis_bangunan'] = $_POST[$prefix_imb.'jenis_bangunan'];
			$arr_data2['tipe_bangunan'] = $_POST[$prefix_imb.'tipe_bangunan'];
		}

		$arr_data2['npwrd'] = $npwrd;
		$arr_data2['kd_rekening'] = $kd_rekening;
		$arr_data2['nm_rekening'] = $nm_rekening;
		$arr_data2['jenis_ketetapan'] = 'SKRD';
		$arr_data2['imb'] = $input_imb;
		$arr_data2['total_retribusi'] = $total_retribusi;
		$arr_data2['fk_skrd'] = $id_skrd;
		
		//check app_nota_perhitungan availability
		$no_nota = $arr_data2['no_nota_perhitungan'];
		$sql = "SELECT id_nota FROM app_nota_perhitungan WHERE kd_rekening='".$kd_rekening."' AND thn_retribusi='".$thn_retribusi."' 
				AND no_nota_perhitungan='".$no_nota."'";
		$id_nota = $db->getOne($sql);

		if(is_null($id_nota) or empty($id_nota))
		{
			$id_nota = $global->get_incrementID('app_nota_perhitungan','id_nota');
			
			$arr_data2['id_nota'] = $id_nota;

			//app_note_perhitungan
			$result = $DML2->save($arr_data2);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed2');
			}
		}else{

			$cond = "id_nota='".$id_nota."'";
			$result = $DML2->update($arr_data2,$cond);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed3');
			}
		}

		//input valuation row
		$arr_DML = array();
		if($input_imb=='0'){
			//app_rincian_nota_perhitungan
			$arr_DML[0] = $DML3;
		}
		else{
			if($thn_dasar_pengenaan=='2012'){
				//app_rincian_nota_perhitungan_imb1
				$arr_DML = array($DML3,);
			}
			else				
				//$DML3 = app_rincian_bangunan_imb2017, $DML4 = app_rincian_prasarana_imb2017,
				$arr_DML = array($DML3,$DML4);
		}

		$input_detail_valuation = input_detail_valuation($input_imb,$global,$id_nota,$arr_DML);

		if(!$input_detail_valuation)
		{
			$db->RollbackTrans();
			die('failed4');
		}

		if($input_imb=='1')
		{
			if($thn_dasar_pengenaan=='2012')
			{
				$id_rincian_nota = $global->get_incrementID('app_rincian_nota_perhitungan_imb2','id_rincian_nota');
				$arr_data3['fk_nota'] = $id_nota;
				$arr_data3['id_rincian_nota'] = $id_rincian_nota;
				$arr_data3['imb_pengganti'] = (isset($_POST[$prefix_imb.'imb_pengganti'])?'1':'0');

				//app_rincian_nota_perhitungan_imb2
				$result = $DML4->save($arr_data3);
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed5');
				}

			}else{
				
				//insert data to app_indeks_terintegrasi_imb2017
				$id_indeks = $global->get_incrementID('app_indeks_terintegrasi_imb2017','id_indeks');
				$arr_data3 = array('bobot_kompleksitas'=>$_POST[$prefix_imb.'bobot_kompleksitas'],
								   'bobot_permanensi'=>$_POST[$prefix_imb.'bobot_permanensi'],
								   'bobot_resiko_kebakaran'=>$_POST[$prefix_imb.'bobot_resiko_kebakaran'],
								   'bobot_zonasi_gempa'=>$_POST[$prefix_imb.'bobot_zonasi_gempa'],
								   'bobot_ketinggian_bangunan'=>$_POST[$prefix_imb.'bobot_ketinggian_bangunan'],
								   'bobot_kepemilikan_bangunan'=>$_POST[$prefix_imb.'bobot_kepemilikan_bangunan'],
								   'indeks_kompleksitas'=>$_POST[$prefix_imb.'indeks_kompleksitas'],
								   'indeks_permanensi'=>$_POST[$prefix_imb.'indeks_permanensi'],
								   'indeks_resiko_kebakaran'=>$_POST[$prefix_imb.'indeks_resiko_kebakaran'],
								   'indeks_zonasi_gempa'=>$_POST[$prefix_imb.'indeks_zonasi_gempa'],
								   'indeks_ketinggian_bangunan'=>$_POST[$prefix_imb.'indeks_ketinggian_bangunan'],
								   'indeks_kepemilikan_bangunan'=>$_POST[$prefix_imb.'indeks_kepemilikan_bangunan'],
								   'nilai_kompleksitas'=>$_POST[$prefix_imb.'nilai_kompleksitas'],
								   'nilai_permanensi'=>$_POST[$prefix_imb.'nilai_permanensi'],
								   'nilai_resiko_kebakaran'=>$_POST[$prefix_imb.'nilai_resiko_kebakaran'],
								   'nilai_zonasi_gempa'=>$_POST[$prefix_imb.'nilai_zonasi_gempa'],
								   'nilai_ketinggian_bangunan'=>$_POST[$prefix_imb.'nilai_ketinggian_bangunan'],
								   'nilai_kepemilikan_bangunan'=>$_POST[$prefix_imb.'nilai_kepemilikan_bangunan'],
								   'total_nilai_indeks'=>$_POST[$prefix_imb.'total_nilai_indeks_terintegrasi'],
								   'fk_nota'=>$id_nota,
								   'id_indeks'=>$id_indeks,
								);

				//app_indeks_terintegrasi_imb2017
				$result = $DML5->save($arr_data3);
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed6');
				}


				$id_perhitungan = $global->get_incrementID('app_perhitungan_imb2017','id_perhitungan');
				$total_penatausahaan = ($_POST[$prefix_imb.'total_penatausahaan']!=''?str_replace(',','',$_POST[$prefix_imb.'total_penatausahaan']):0);

				$arr_data3 = array('total_luas_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'total_luas_bangunan']),
								   'indeks_prasarana'=>str_replace(',','',$_POST[$prefix_imb.'indeks_prasarana']),
								   'total_nilai_indeks_terintegrasi'=>str_replace(',','',$_POST[$prefix_imb.'total_nilai_indeks_terintegrasi']),
								   'indeks_penggunaan_gedung'=>str_replace(',','',$_POST[$prefix_imb.'indeks_penggunaan_gedung']),
								   'indeks_waktu_penggunaan'=>str_replace(',','',$_POST[$prefix_imb.'indeks_waktu_penggunaan']),
								   'indeks_bangunan_bawah_permukaan_tanah'=>str_replace(',','',$_POST[$prefix_imb.'indeks_bangunan_bawah_permukaan_tanah']),
								   'imb_pengganti'=>isset($_POST[$prefix_imb.'indeks_pengganti'])?'1':'0',
								   'harga_satuan_retribusi_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'harga_satuan_retribusi_bangunan']),
								   'total_retribusi_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'total_retribusi_bangunan']),
								   'total_retribusi_prasarana'=>str_replace(',','',$_POST[$prefix_imb.'total_retribusi_prasarana']),
								   'total_penatausahaan'=>$total_penatausahaan,
								   'grand_total_retribusi'=>str_replace(',','',$_POST[$prefix_imb.'grand_total_retribusi']),
								   'fk_nota'=>$id_nota,
								   'id_perhitungan'=>$id_perhitungan
								);
								
				//app_perhitungan_imb2017
				$result = $DML6->save($arr_data3);

				if(!$result)
				{
					$db->RollbackTrans();
					die('failed7');
				}
			}
		}
	/*	$sql_session = "INSERT INTO app_session (session_id,usr_id,last_access,ip,user_agent,status,ctime,challenge,session_content) 
		VALUES ('".$session_id."','".$user_id."','".$last_access."','".$ip."','".$_SERVER['HTTP_USER_AGENT']."','1','".$ctime."','".$challenge."','Pendataan:Input:NOSKRD:".$no_skrd."NPWRD:".$npwrd."')";


		$result_ = $db->Execute($sql_session);
	if (!$result_)
		{
 var_dump ($sql_session);
		return 'failed';
		}*/
	

	}
	else if($act=='edit')
	{		
		$id_nota=$_POST['id_nota'];
		$id_skrd=$_POST['fk_skrd'];

		$cond = "id_skrd='".$id_skrd."'";

		$result = $DML1->update($arr_data1,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}

		if($input_imb=='0')
		{
			$total_retribusi = str_replace(",","",$_POST['total_perhitungan_nr']);
		}else{
			$total_retribusi = str_replace(",","",$thn_dasar_pengenaan=='2012'?$_POST[$prefix_imb.'grand_total_imb']:$_POST[$prefix_imb.'grand_total_retribusi']);
		}

		$cond = "id_nota='".$id_nota."'";
		$arr_data2['total_retribusi'] = $total_retribusi;

		$result = $DML2->update($arr_data2,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed2');
		}

		//input valuation row
		$arr_DML = array();
		if($input_imb=='0')
			$arr_DML[0] = $DML3;
		else{
			if($thn_dasar_pengenaan=='2012')			
				$arr_DML = array($DML3,);
			else
				$arr_DML = array($DML3,$DML4);
		}

		$input_detail_valuation = input_detail_valuation($input_imb,$global,$id_nota,$arr_DML);
		
		if(!$input_detail_valuation)
		{
			$db->RollbackTrans();
			die('failed3');
		}

		if($input_imb=='1')
		{

			if($thn_dasar_pengenaan=='2012')
			{

				$arr_data3['imb_pengganti'] = (isset($_POST[$prefix_imb.'imb_pengganti'])?'1':'0');
				
				$id_rincian_nota2 = $_POST[$prefix_imb.'id_rincian_nota2'];
				$cond = "id_rincian_nota='".$id_rincian_nota2."'";

				$result = $DML4->update($arr_data3,$cond);
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed4');
				}
				
			}else{
				
				//insert data to app_indeks_terintegrasi_imb2017
				$id_indeks = $_POST[$prefix_imb.'id_indeks'];				
				$arr_data3 = array('bobot_kompleksitas'=>$_POST[$prefix_imb.'bobot_kompleksitas'],
								   'bobot_permanensi'=>$_POST[$prefix_imb.'bobot_permanensi'],
								   'bobot_resiko_kebakaran'=>$_POST[$prefix_imb.'bobot_resiko_kebakaran'],
								   'bobot_zonasi_gempa'=>$_POST[$prefix_imb.'bobot_zonasi_gempa'],
								   'bobot_ketinggian_bangunan'=>$_POST[$prefix_imb.'bobot_ketinggian_bangunan'],
								   'bobot_kepemilikan_bangunan'=>$_POST[$prefix_imb.'bobot_kepemilikan_bangunan'],
								   'indeks_kompleksitas'=>$_POST[$prefix_imb.'indeks_kompleksitas'],
								   'indeks_permanensi'=>$_POST[$prefix_imb.'indeks_permanensi'],
								   'indeks_resiko_kebakaran'=>$_POST[$prefix_imb.'indeks_resiko_kebakaran'],
								   'indeks_zonasi_gempa'=>$_POST[$prefix_imb.'indeks_zonasi_gempa'],
								   'indeks_ketinggian_bangunan'=>$_POST[$prefix_imb.'indeks_ketinggian_bangunan'],
								   'indeks_kepemilikan_bangunan'=>$_POST[$prefix_imb.'indeks_kepemilikan_bangunan'],
								   'nilai_kompleksitas'=>$_POST[$prefix_imb.'nilai_kompleksitas'],
								   'nilai_permanensi'=>$_POST[$prefix_imb.'nilai_permanensi'],
								   'nilai_resiko_kebakaran'=>$_POST[$prefix_imb.'nilai_resiko_kebakaran'],
								   'nilai_zonasi_gempa'=>$_POST[$prefix_imb.'nilai_zonasi_gempa'],
								   'nilai_ketinggian_bangunan'=>$_POST[$prefix_imb.'nilai_ketinggian_bangunan'],
								   'nilai_kepemilikan_bangunan'=>$_POST[$prefix_imb.'nilai_kepemilikan_bangunan'],
								   'total_nilai_indeks'=>$_POST[$prefix_imb.'total_nilai_indeks_terintegrasi'],								   
								);
	
				$cond = "id_indeks='".$id_indeks."'";
				$result = $DML5->update($arr_data3,$cond);
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed5');
				}


				$id_perhitungan = $_POST[$prefix_imb.'id_perhitungan'];

				$total_penatausahaan = ($_POST[$prefix_imb.'total_penatausahaan']!=''?str_replace(',','',$_POST[$prefix_imb.'total_penatausahaan']):0);

				$arr_data3 = array('total_luas_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'total_luas_bangunan']),
								   'indeks_prasarana'=>str_replace(',','',$_POST[$prefix_imb.'indeks_prasarana']),
								   'total_nilai_indeks_terintegrasi'=>str_replace(',','',$_POST[$prefix_imb.'total_nilai_indeks_terintegrasi']),
								   'indeks_penggunaan_gedung'=>str_replace(',','',$_POST[$prefix_imb.'indeks_penggunaan_gedung']),
								   'indeks_waktu_penggunaan'=>str_replace(',','',$_POST[$prefix_imb.'indeks_waktu_penggunaan']),
								   'indeks_bangunan_bawah_permukaan_tanah'=>str_replace(',','',$_POST[$prefix_imb.'indeks_bangunan_bawah_permukaan_tanah']),
								   'imb_pengganti'=>isset($_POST[$prefix_imb.'indeks_pengganti'])?'1':'0',
								   'harga_satuan_retribusi_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'harga_satuan_retribusi_bangunan']),
								   'total_retribusi_bangunan'=>str_replace(',','',$_POST[$prefix_imb.'total_retribusi_bangunan']),
								   'total_retribusi_prasarana'=>str_replace(',','',$_POST[$prefix_imb.'total_retribusi_prasarana']),
								   'total_penatausahaan'=>$total_penatausahaan,
								   'grand_total_retribusi'=>str_replace(',','',$_POST[$prefix_imb.'grand_total_retribusi']),
								);

				$cond = "id_perhitungan='".$id_perhitungan."'";
				$result = $DML6->update($arr_data3,$cond);
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed6');
				}
			}
		}		
	//	$sql_session_ed = "INSERT INTO app_session (session_id,usr_id,last_access,ip,status,ctime,session_content) 
	//	VALUES ('".$session_id."','".$user_id."','".$last_access."','".$ip."','1','".$ctime."','Pendataan:EDIT-NOSKRD:".$no_skrd."NPWRD:".$npwrd."')";


$result_ = $db->Execute($sql_session_ed);
if (!$result_)
{
 
 return 'failed';
}
	}
	else if($act=='delete')
	{
		$id_nota = $_POST['id'];
		$fk_skrd = $_POST['fk_skrd'];

		$cond = "id_nota='".$id_nota."'";		
		$result = $DML2->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}		
		//	$sql_session_del = "INSERT INTO app_session (session_id,usr_id,last_access,ip,status,ctime,session_content) 
		//	VALUES ('".$session_id."','".$user_id."','".$last_access."','".$ip."','1','".$ctime."','Pendataan:DELETE-NOSKRD:".$fk_skrd."NPWRD:".$npwrd."')";


			$result_ = $db->Execute($sql_session_del);
			if (!$result_)
			{
 
			return 'failed';
			}
	
	$sql = "SELECT COUNT(1) as n_nota FROM app_nota_perhitungan WHERE(fk_skrd='".$fk_skrd."')";
		$n_nota = $db->getOne($sql);
		if($n_nota==0)
		{
			$cond = "id_skrd='".$fk_skrd."'";
			$result = $DML1->delete($cond);
			
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}					
		}

		$cond = "fk_nota='".$id_nota."'";
		$result = $DML3->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if($input_imb=='1')
		{

			$cond = "fk_nota='".$id_nota."'";
			$result = $DML4->delete($cond);
			
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}

			if($thn_dasar_pengenaan=='2017'){
				$cond = "fk_nota='".$id_nota."'";
				$result = $DML5->delete($cond);
				
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed');
				}

				$cond = "fk_nota='".$id_nota."'";
				$result = $DML6->delete($cond);
				
				if(!$result)
				{
					$db->RollbackTrans();
					die('failed');
				}				
			}
		}

	}	    

	$db->CommitTrans();

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    //fetching data to generate list of data

    $list_sql .= " WHERE (a.npwrd='".$npwrd."') AND (a.thn_retribusi='".$thn_retribusi."')";
  	$list_sql .= " ORDER BY a.no_nota_perhitungan ASC";
  	
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>