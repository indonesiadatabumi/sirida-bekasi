<?php 



if(isset($_POST['search'])){
    $search = strtolower($_POST['search']);

    $query = "SELECT npwrd,wp_wr_nama FROM app_skrd_pasar WHERE  npwrd like'%".$search."%' "; //npwrd like'%".$search."%' or
	$queryx=$db->Execute($query);
	//$result = $queryx->FetchRow();
 
    
    while($row = $queryx->FetchRow()){
        $response[] = array("value"=>$row['wp_wr_nama'],"label"=>$row['npwrd']);
    }

    echo json_encode($response);
}

//exit;


?>