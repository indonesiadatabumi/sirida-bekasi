<?php
	$cond = "";
  	$lbl_status = "";
  	$lbl_tipe = ($src_tipe=='1'?'Non ':'').'Karcis';
  	if($src_status=='1'){
    	$cond .= "a.status_ketetapan='0' AND a.status_bayar='0'";
    	$lbl_status = "Belum ditetapkan";
  	}else if($src_status){
    	$cond .= "a.status_ketetapan='1' AND a.status_bayar='0'";
    	$lbl_status = "Sudah ditetapkan";
  	}else{
    	$cond .= "a.status_ketetapan='1' AND a.status_bayar='1'";
    	$lbl_status = "Terbayar";
  	}

  	if($src_tgl_skrd_awal!='' and $src_tgl_skrd_akhir!=''){
    	$cond .= " AND (a.tgl_skrd BETWEEN to_date('".$src_tgl_skrd_awal."','dd-mm-yyyy') AND to_date('".$src_tgl_skrd_akhir."','dd-mm-yyyy'))";
  	}else if($src_tgl_skrd_awal!='' and $src_tgl_skrd_akhir==''){
    	$cond .= "AND a.tgl_skrd>=to_date('".$src_tgl_skrd_awal."','dd-mm-yyyy')";
  	}else if($src_tgl_skrd_awal=='' and $src_tgl_skrd_akhir!=''){
    	$cond .= "AND a.tgl_skrd<=to_date('".$src_tgl_skrd_akhir."','dd-mm-yyyy')";
  	}

  	$join_sql = "";
  	$join_fields = "";
  	if($src_tipe=='1'){
    	$join_sql = "(SELECT fk_skrd,total_retribusi FROM app_nota_perhitungan)";
    	$join_fields = "total_retribusi";
  	}else{
    	$join_sql = "(SELECT fk_skrd,nilai_total_perforasi,total_retribusi FROM app_permohonan_karcis)";
    	$join_fields = "nilai_total_perforasi,total_retribusi";
  	}

  	$list_sql = "SELECT a.*,".$join_fields." FROM app_skrd as a INNER JOIN ".$join_sql." b 
               ON (a.id_skrd=b.fk_skrd) 
               WHERE ".$cond." ORDER BY a.no_skrd ASC";  

  	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();
?>