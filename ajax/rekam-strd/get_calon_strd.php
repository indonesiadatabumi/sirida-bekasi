<?php
require_once("inc/init.php");
require_once("function.php");

$jenis_retribusi = $_POST['jenis_retribusi'];
$periode = $_POST['periode'];
$no_skrd = $_POST['no_skrd'];

$sql = "SELECT a.*, b.total_retribusi FROM app_skrd a LEFT JOIN app_nota_perhitungan b ON a.id_skrd=b.fk_skrd WHERE a.kd_rekening='$jenis_retribusi' AND a.thn_retribusi='$periode' AND a.no_skrd='$no_skrd'";
$result1 = $db->Execute($sql);
$data = $result1->fields;

if ($data === false || $data === null) {
    $response = [
        'response_code' => '99',
        'response_message' => 'data tidak ditemukan'
    ];
} else {
    $tgl_penetapan = $data['tgl_penetapan'];
    $tgl_jatuh_tempo = date('Y-m-d', strtotime($tgl_penetapan . ' +30 days'));
    $kode_billing = $data['kd_billing'];
    $id_skrd = $data['id_skrd'];
    $npwrd = $data['npwrd'];
    $wp_wr_nama = $data['wp_wr_nama'];
    $wp_wr_alamat = $data['wp_wr_alamat'] . " Kel. " . $data['wp_wr_lurah'] . " Kec. " . $data['wp_wr_camat'] . " " . $data['wp_wr_kabupaten'];
    $bln_retribusi = nama_bulan($data['bln_retribusi']);
    $thn_retribusi = $data['thn_retribusi'];

    $sql2 = "SELECT * FROM app_pembayaran_retribusi WHERE kd_billing='$kode_billing'";
    $result2 = $db->Execute($sql2);
    $data2 = $result2->fields;

    if ($data2 === false || $data2 === null) {
        $bulan_pengenaan = bulan_pengenaan($tgl_jatuh_tempo);
        $tgl_setoran = '';
        $jumlah_setoran = $data['total_retribusi'];

        $data_response = [
            'id_skrd' => $id_skrd,
            'npwrd' => $npwrd,
            'wp_wr_nama' => $wp_wr_nama,
            'wp_wr_alamat' => $wp_wr_alamat,
            'bln_retribusi' => $bln_retribusi,
            'thn_retribusi' => $thn_retribusi,
            'tgl_penetapan' => $tgl_penetapan,
            'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
            'tgl_setoran' => $tgl_setoran,
            'jumlah_setoran' => $jumlah_setoran,
            'bulan_pengenaan' => $bulan_pengenaan
        ];

        $response = [
            'response_code' => '00',
            'response_message' => 'success',
            'data' => $data_response
        ];
    } else {
        $tgl_pembayaran = $data2['tgl_pembayaran'];

        if ($tgl_jatuh_tempo > $tgl_pembayaran) {
            $response = [
                'response_code' => '99',
                'response_message' => 'data tidak ditemukan'
            ];
        } else {
            $bulan_pengenaan = bulan_pengenaan($tgl_jatuh_tempo);
            $tgl_setoran = date("Y-m-d", strtotime($data2['tgl_pembayaran']));
            $jumlah_setoran = $data2['total_bayar'];

            $data_response = [
                'id_skrd' => $id_skrd,
                'npwrd' => $npwrd,
                'wp_wr_nama' => $wp_wr_nama,
                'wp_wr_alamat' => $wp_wr_alamat,
                'bln_retribusi' => $bln_retribusi,
                'thn_retribusi' => $thn_retribusi,
                'tgl_penetapan' => $tgl_penetapan,
                'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
                'tgl_setoran' => $tgl_setoran,
                'jumlah_setoran' => $jumlah_setoran,
                'bulan_pengenaan' => $bulan_pengenaan
            ];

            $response = [
                'response_code' => '00',
                'response_message' => 'success',
                'data' => $data_response
            ];
        }
    }
}

echo json_encode($response);
