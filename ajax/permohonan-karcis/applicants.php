<?php    
    require_once("inc/init.php");
    

    $npwrd = $_POST['npwrd'];

    $sql = "SELECT a.id_pegawai,a.nama FROM app_ref_pegawai as a INNER JOIN 
    		  (SELECT kd_instansi FROM app_ref_instansi as x 
               INNER JOIN (SELECT nm_wp_wr FROM app_reg_wr WHERE npwrd='".$npwrd."') as y ON (x.nm_instansi=y.nm_wp_wr)) as b
    		ON (a.kd_instansi=b.kd_instansi)";

    $result = $db->Execute($sql);

    $applicants = "<option value=''></option>";

    if($result->RecordCount()>0){
    	while($row=$result->FetchRow()){
    		$applicants .= "<option value='".$row['id_pegawai']."'>".$row['nama']."</option>";
    	}
    }else{
    	$sql = "SELECT id_pegawai,nama FROM app_ref_pegawai";
    	$result = $db->Execute($sql);
    	while($row=$result->FetchRow()){
    		$applicants .= "<option value'".$row['id_pegawai']."'>".$row['nama']."</option>";
    	}

    }
    echo $applicants;
?>