<?php
require_once("inc/init.php");
require_once("function.php");

$bulan_realisasi = $_POST['bulan_realisasi'];
$tahun_realisasi = $_POST['tahun_realisasi'];
$no_skrd = $_POST['no_skrd'];
$jenis_retribusi = $_POST['jenis_retribusi'];
$id_skrd = $_POST['id_skrd'];
$npwrd = $_POST['npwrd'];
$strd_nomor = $_POST['strd_nomor'];
$wp_wr_nama = $_POST['wp_wr_nama'];
$tgl_proses = $_POST['tgl_proses'];
$wp_wr_alamat = $_POST['wp_wr_alamat'];
$periode = $_POST['periode'];
$bln_retribusi = $_POST['bln_retribusi'];
$thn_retribusi = $_POST['thn_retribusi'];
$tgl_penetapan = $_POST['tgl_penetapan'];
$tgl_jatuh_tempo = date('Y-m-d', strtotime($tgl_proses . ' +30 days'));
if (empty($_POST['tgl_setoran'])) {
    $tgl_setoran = null;
} else {
    $tgl_setoran = $_POST['tgl_setoran'];
}
$jumlah_setoran = str_replace('.', '', $_POST['jumlah_setoran']);
$bulan_pengenaan = $_POST['bulan_pengenaan'];
$tarif = $_POST['tarif'];
$pajak_terhutang = str_replace('.', '', $_POST['pajak_terhutang']);
$kode_billing = get_billing_code($db, $id_skrd);

// Ambil ID terakhir
$sql_id = "SELECT MAX(strd_id) as max_id FROM app_strd";
$res_id = $db->Execute($sql_id);
$strd_id = ($res_id && !$res_id->EOF) ? ($res_id->fields['max_id'] + 1) : 1;

$sql = "INSERT INTO app_strd (
    strd_id, 
    strd_jenis_retribusi, 
    strd_tgl_proses, 
    strd_jatuh_tempo, 
    strd_periode, 
    strd_nomor, 
    strd_skrd_id,
    strd_tgl_setoran,
    strd_jumlah_setoran, 
    strd_bulan_pengenaan, 
    strd_pajak, 
    strd_kode_billing
) VALUES (
    $strd_id, 
    '$jenis_retribusi',
    '$tgl_proses',
    '$tgl_jatuh_tempo',
    '$periode',
    '$strd_nomor',
    '$id_skrd',
    " . ($tgl_setoran === null ? "NULL" : "'$tgl_setoran'") . ",
    $jumlah_setoran,
    $bulan_pengenaan,
    $pajak_terhutang,
    '$kode_billing'
)";
$result1 = $db->Execute($sql);

if ($result1) {
    $response = [
        'response_code' => '00',
        'response_message' => 'success'
    ];
} else {
    $response = [
        'response_code' => '99',
        'response_message' => 'Penyimpanan gagal'
    ];
}

echo json_encode($response);
