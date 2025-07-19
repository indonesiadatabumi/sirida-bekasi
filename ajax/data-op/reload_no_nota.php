<?php

require_once("inc/init.php");
require_once("../../lib/global_obj.php");

$global = new global_obj($db);

$kd_rekening = $_POST['kd_rekening'];
$no_nota = $global->get_new_bill_number($kd_rekening);

echo $no_nota;
