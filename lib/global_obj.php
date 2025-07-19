<?php
	class global_obj
	{
		protected $_db;
		
		public function __construct($db=null)
		{
			$this->_db=$db;			
		}
		
		function real_escape_string($input)
		{
			$result = preg_replace("/'/i","\'",$input);			
			return $result;
		}		

		function dropseparate_specialChar($str)
		{
			$result = array($str,'','');
			if(strlen($str)>1)
			{
				$arr_specialChar = array('`','~','!','@','#',
										 '$','%','^','&','*',
										 '(',')','_','-','+',
										 '=',',','.','<','>',
										 '/','?',';','\'',':',
										 '"','[',']','\\','|');
				$firstChar = substr($str,0,1);
				$lastChar = substr($str,-1,1);

				$x = array_search($firstChar,$arr_specialChar,true);
				$y = array_search($lastChar,$arr_specialChar,true);
							
				$str1 = ($x?substr($str,1,strlen($str)-1):$str);
				$str2 = ($y?substr($str1,0,strlen($str1)-1):$str);
				$result [0] = $str2;
				$result [1] = ($x?$arr_specialChar[$x]:'');
				$result [2] = ($y?$arr_specialChar[$y]:'');
			}			

			return $result;
		}		
		

		function get_incrementID($table,$pk)
		{
			$sql = "SELECT ".$pk." FROM public.".$table." ORDER BY ".$pk." DESC";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			$new = 1;
			if($result->RecordCount()>0)	
			{
				$row = $result->FetchRow();
				$new = (int) $row[$pk] + 1;				
			}
			return $new;
		}

		function get_registerNum2($table,$pk)
		{
			$initials = array('MEN','USRT','USR');
			$num = '';
			switch($table)
			{
				case 'app_menu':$num=0;break;
				case 'app_user_types':$num=1;break;
				case 'app_user':$num=2;break;
			}

			
			$sql = "SELECT MAX(".$pk.") as last_ordnum FROM ".$table." WHERE(".$pk." LIKE '".$initials[$num]."%')";
			

			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			
			$order_num = 1;
			$len1 = strlen($initials[$num])+1;
			$len2 = 10-$len1;

			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_ordnum'], $len1, $len2) + 1;
			}
			
			$regnum = $initials[$num]."-".sprintf("%0".$len2."s", $order_num);

			return $regnum;

		}
		
		function get_registerNum()
		{
		//	$curr_year = date('Y');
		//	$sql = "SELECT MAX(no_registrasi) as last_regnum FROM public.app_reg_wr WHERE no_registrasi LIKE '".$curr_year."%'";
		$sql = "SELECT MAX(no_registrasi) as last_regnum FROM public.app_reg_wr ";
		$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				//$order_num = (int) substr($row['last_regnum'],4,4) + 1;
				$order_num = (int) $row['last_regnum'] + 1;
			}			

			//$regnum = $curr_year.sprintf("%04s", $order_num);
			$regnum = sprintf("%04s", $order_num);
			return $regnum;
		}

		function get_ticketInventoryNum($id_permohonan)
		{
			$sql = "SELECT no_persediaan FROM public.app_persediaan_benda_berharga WHERE fk_permohonan='".$id_permohonan."' ORDER BY no_persediaan DESC";
			$lastNum = $this->_db->getOne($sql);
			
			$newNum = (!is_null($lastNum) && !empty($lastNum)?$lastNum:0) + 1;
						
			return $newNum;
		}		

		function get_ticket_seriNum($kd_rekening){			
			$id_ret = $this->_db->getOne("SELECT id_jenis_retribusi FROM app_ref_jenis_retribusi WHERE kd_rekening='".$kd_rekening."'");
			$prefix = date('y').sprintf('%02s',$id_ret);
			$sql = "SELECT MAX(no_seri) as last_seri FROM public.app_permohonan_karcis WHERE no_seri LIKE '".$prefix."%'";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());
			$order_num = 1;
			if($result->RecordCount()>0){
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_seri'],4,3)+1;
			}
			$serinum = $prefix.sprintf('%03s',$order_num);
			return $serinum;
		}

		function get_user_id()
		{
			$curr_year = date('Y');
			$sql = "SELECT MAX(usr_id) as last_id FROM public.app_user";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_id'],4,6) + 1;
			}			

			$userid = 'USR-'.sprintf("%06s", $order_num);
			return $userid;
		}

		function get_new_number($type,$kd_rekening='')
		{
			$table = "";
			$field = "";
			$new_number = "";

			if($type=='1' or $type=='2'){				
				$table='app_skrd';
				$field="no_skrd";				
			}else if($type=='3'){
				$table = 'app_ba_stbb';
				$field = 'no_berita_acara';
			}
			$curr_year = date('Y');
			
			
			$sql = "SELECT MAX(".$field.") as last_num FROM public.".$table." WHERE ".($kd_rekening!=''?"kd_rekening='".$kd_rekening."' AND ":"");
			$sql .= "thn_retribusi='".$curr_year."'";
			
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			
			$new_number = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$new_number = (int) $row['last_num'] + 1;
			}
			

			return $new_number;	
		}

		function get_new_bill_number($kd_rekening)
		{
			return $this->get_new_number('1',$kd_rekening);
		}

		function get_new_skrd_number($kd_rekening)
		{
			return $this->get_new_number('2',$kd_rekening);
		}

		function get_new_ba_number(){
			return $this->get_new_number('3');
		}

		function get_district_name($id)
		{
			$sql = "SELECT camat_nama as name FROM public.kecamatan WHERE(camat_id='".$id."')";
			$name = $this->_db->getOne($sql);
			return $name;
		}

		function get_district_id($name)
		{
			$sql = "SELECT camat_id as id FROM kecamatan WHERE(LOWER(camat_nama)='".strtolower($name)."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_village_name($id)
		{
			$sql = "SELECT lurah_nama as name FROM kelurahan WHERE(lurah_id='".$id."')";
			$name = $this->_db->getOne($sql);			
			return $name;
		}
		
		function get_village_id($name,$dis_id)
		{
			$sql = "SELECT lurah_id as id FROM kelurahan  WHERE(LOWER(lurah_nama)='".strtolower($name)."') AND (lurah_kecamatan='".$dis_id."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_npwrd($type,$district_id='')
		{
		//	$curr_year = date('y');
			$curr_year = '20';

			$search_value = 'R.'.($type=='1'?$curr_year:$district_id);

			$sql = "SELECT MAX(npwrd) as last_npwrd FROM app_reg_wr WHERE(npwrd LIKE '".$search_value."%')";

		    $result = $this->_db->Execute($sql);
		    if(!$result)
		        die('ERROR:terjadi kesalahan!');

		    $order_num = 1;
		    if($result->RecordCount()>0)
		    {
		        $row = $result->FetchRow();
		        $order_num = (int) substr($row['last_npwrd'],4) + 1;
		    }


		    $npwrd = 'R.'.($type=='1'?$curr_year:$district_id).sprintf("%04s", $order_num);
		    return $npwrd;
		}
		
		function get_no_npwrd($type,$district_id='')
		{
				
		
			$sql = "SELECT MAX(npwrd) as last_npwrd FROM app_no_npwrd ";

		    $result = $this->_db->Execute($sql);
		    if(!$result)
		        die('ERROR:terjadi kesalahan!');

		    $order_num = 1;
		    if($result->RecordCount()>0)
		    {
		        $row = $result->FetchRow();
		        $order_num = (int) substr($row['last_npwrd'],0) + 1;
			
		    }

		   $npwrd = ($type=='').sprintf("%04s", $order_num);
		    return $npwrd;
			
			
		}
		function get_no_registerNum()
		{
			$sql = "SELECT MAX(no_registrasi) as last_regnum FROM public.app_no_registrasi ";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				//$order_num = (int) substr($row['last_regnum'],4,4) + 1;
				$order_num = (int) $row['last_regnum'] + 1;
			}			

			//$regnum = $curr_year2.sprintf("%04s", $order_num);
			$regnum = sprintf("%04s", $order_num);
			
			return $regnum;
		}

		// function get_billing_code($type)
		// {
		// 	$prefix	= $type.'0';
		// 	$stamp1	= date("m");
		// 	$stamp2	= date("d");
		// 	$len = 5; 
		// 	$base = '123456789'; 
		// 	$max = strlen($base)-1;
		// 	$activatecode='';
			
		// 	mt_srand((double)microtime()*1000000);
			
		// 	while (strlen($activatecode)<$len+1)
		// 	{
		// 		$activatecode .= $base{mt_rand(0,$max)};
		// 	}
		// 	$billing_code = $prefix.$stamp1.$activatecode.$stamp2;
		// 	return $billing_code;
		// }

		function get_billing_code($id_skrd)
		{
			$sql = "SELECT b.kode_kategori AS kode_kategori FROM app_skrd AS a LEFT JOIN app_ref_jenis_retribusi AS b ON a.kd_rekening=b.kd_rekening WHERE a.id_skrd='$id_skrd'";
			$kode_kategori = $this->_db->getOne($sql);
			$sql_skrd = "SELECT no_skrd AS no_skrd FROM app_skrd WHERE id_skrd='$id_skrd'";
			$no_skrd = $this->_db->getOne($sql_skrd);
			$stamp2	= date("His");
			$len = 2; 
			$base = '123456789'; 
			$max = strlen($base)-1;
			$activatecode='';
			
			mt_srand((double)microtime()*1000000);
			
			while (strlen($activatecode)<$len)
			{
				$activatecode .= $base{mt_rand(0,$max)};
			}
			$billing_code = $kode_kategori.$activatecode.$stamp2.$no_skrd;
			return $billing_code;
		}

		function get_ntpd()
		{
			$stamp = date("Ymdhis");			
			$orderid = $stamp;
			$orderid = str_replace(".","",$orderid);

			return $orderid;	
		}

		function get_payment_position($billing_code)
		{
			$sql = "SELECT MAX(pembayaran_ke) as pembayaran_terakhir FROM app_pembayaran_retribusi WHERE(kd_billing='".$billing_code."') AND (status_reversal='0')";
			$last_payment = $this->_db->getOne($sql);
			$payment_position = (!is_null($last_payment) && !empty($last_payment)?$last_payment+1:1);
			return $payment_position;
		}

		function get_total_payment($billing_code)
		{
			$sql = "SELECT SUM(total_bayar) as total_pembayaran FROM app_pembayaran_retribusi WHERE(kd_billing='".$billing_code."') AND (status_reversal='0')";
			$total_payment = $this->_db->getOne($sql);
			return $total_payment;
		}

		function get_retribution_ref($kd_rekening,$field)
		{
			$sql = "SELECT ".$field." FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')";
			$result = $this->_db->getOne($sql);
			return $result;
		}

		function get_system_params()
		{
			$sql = "SELECT * FROM app_system_params";
			$result = $this->_db->Execute($sql);
			if(!$result)
				return false;
			$system_params = array();
			while($row=$result->FetchRow())
			{
				$system_params[$row['id']] = $row['value'];
			}

			return $system_params;
		}

		function format_account_code($account_code)
		{
			$result = '';
			if(strlen($account_code)==7)
			{
				$result = substr($account_code,0,1).'.'.substr($account_code,1,1).'.'.substr($account_code,2,1).'.'.substr($account_code,3,2).'.'.substr($account_code,5,2);
			}else {
				$result = $account_code;
			}
			return $result;
		}

		function log_akses($ip, $aksi)
		{
			$ctime = date('Y-m-d H:i:s');
			$usr_id = $_SESSION['usr_id'];
	
			$sql_log = "INSERT INTO log_sirida (user_akses,tgl_akses,ip,browser,aksi) 
							VALUES ('" . $usr_id . "','" . $ctime . "','" . $ip . "','" . $_SERVER['HTTP_USER_AGENT'] . "','" . $aksi . " ')";
			$this->_db->Execute($sql_log);
		}

	}
?>