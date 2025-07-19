<?php
	require_once("inc/init.php");

	$sql = "SELECT * FROM app_rincian_nota_perhitungan_imb2 order by id_rincian_nota asc";
	$result = $db->Execute($sql);

	$db->BeginTrans();
	while($row = $result->fetchRow()){
		$updateSql = "UPDATE app_rincian_nota_perhitungan_imb2 SET grand_total_imb='".$row['total_nilai_imb']."', imb_pengganti='0' 
						WHERE id_rincian_nota='".$row['id_rincian_nota']."'";
		
		$result2 = $db->Execute($updateSql);
		if(!$result2){
			$db->RollbackTrans();
			die('terjadi kesalahan');
		}
		echo $updateSql.'<br />';
	}
	$db->CommitTrans();
?>