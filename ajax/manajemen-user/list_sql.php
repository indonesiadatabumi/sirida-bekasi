<?php
	$list_sql = "SELECT a.*,b.name as tipe_user FROM app_user as a LEFT JOIN app_user_types as b ON (a.usr_type_id=b.usr_type_id)";
?>