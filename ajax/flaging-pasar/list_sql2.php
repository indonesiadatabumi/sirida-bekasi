<?php
$sql_idP="select max(id_pembayaran)as last from payment_retribusi_pasar";
$maxidx=$db->Execute($sql_idP);


$sql_idSKRD="select max(no_skrd)as last from app_skrd_pasar";
$maxidSKRDx=$db->Execute($sql_idSKRD);

$sql_idSKRDz="select max(id_skrd)as last from app_skrd_pasar";
$maxidSKRDzx=$db->Execute($sql_idSKRDz);



?>