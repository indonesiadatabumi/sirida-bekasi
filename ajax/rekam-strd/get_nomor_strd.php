<?php
require_once("inc/init.php");

$jenis_retribusi = $_POST['jenis_retribusi'];
$periode = $_POST['periode'];

$sql = "SELECT MAX(strd_nomor) as strd_nomor FROM app_strd WHERE strd_jenis_retribusi='$jenis_retribusi' AND strd_periode='$periode'";
$result1 = $db->Execute($sql);

$strd_nomor = '0001'; // Default jika belum ada data

if ($result1 && !$result1->EOF) {
    $max_nomor = $result1->fields['strd_nomor'];

    if (!empty($max_nomor)) {
        $next_nomor = (int)$max_nomor + 1;
        $strd_nomor = str_pad($next_nomor, 4, '0', STR_PAD_LEFT);
    }
}

$response = [
    'response_code' => '00',
    'response_message' => 'success',
    'data' => $strd_nomor
];

header('Content-Type: application/json');
echo json_encode($response);
