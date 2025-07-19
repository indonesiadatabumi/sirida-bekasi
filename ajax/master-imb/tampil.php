<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML1 = new DML('app_reg_wr',$db);
	$DML2 = new DML('app_ref_instansi',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	
    $id_name = 'npwrd';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('no_registrasi','nm_wp_wr','alamat_wp_wr','no_tlp','kelurahan','kecamatan','kota','kd_pos',
    					'tgl_form_diterima','tgl_batas_kirim','tgl_pendaftaran','npwrd','tipe_wr','kd_rekening');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'wr-reg-form';
	
	$no_registrasi = ($act=='add'?$global->get_registerNum():$curr_data['no_registrasi']);
	$no_registrasi = substr($no_registrasi,4,4);
	//$result = array();
	//array_push($result, array('no_registrasi' => $no_registrasi));
	//echo json_encode(array("result" => $result));
	//echo json_encode($no_registrasi);
	?>
	<script type="text/javascript" src="ajax/master-wr1/jquery.js"></script>