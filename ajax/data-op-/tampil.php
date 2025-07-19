<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");


$sql = "SELECT a.*,b.no_skrd,b.tgl_skrd,c.kd_rekening,c.jenis_retribusi,c.dasar_hukum_pengenaan".($input_imb=='1'?',d.*':'')." FROM app_nota_perhitungan as a 
    			LEFT JOIN app_skrd as b ON (a.fk_skrd=b.id_skrd)    			
    			LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)";
    	

    	$sql .= "WHERE(a.id_nota)";

    	$result2 = $db->Execute($sql);
    	if(!$result2)
    		echo ($db->ErrorMsg());
$result = array();
 $curr_data = $result2->FetchRow();


//while($row=mysqli_fetch_array($sql)){

	array_push($result, array('no_skrd' => $curr_data[0]));
	
//}
echo json_encode(array("result" => $result));


?>