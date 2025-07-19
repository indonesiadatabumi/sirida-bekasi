<?php
function bulan_pengenaan($tgl_jatuh_tempo, $tgl_skrg = null)
{
    if (is_null($tgl_skrg)) {
        $tgl_skrg = date('Y-m-d');
    }

    $jatuh_tempo = new DateTime($tgl_jatuh_tempo);
    $sekarang = new DateTime($tgl_skrg);

    if ($sekarang < $jatuh_tempo) {
        return 0; // Belum jatuh tempo
    }

    $selisih = $jatuh_tempo->diff($sekarang);

    $jumlah_bulan = ($selisih->y * 12) + $selisih->m;

    return $jumlah_bulan;
}

function nama_bulan($angka_bulan)
{
    $nama_bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    return $nama_bulan[(int)$angka_bulan] ?? 'Bulan tidak valid';
}

function get_billing_code($db, $id_skrd)
{
    $sql = "SELECT b.kode_kategori AS kode_kategori FROM app_skrd AS a LEFT JOIN app_ref_jenis_retribusi AS b ON a.kd_rekening=b.kd_rekening WHERE a.id_skrd='$id_skrd' LIMIT 1";
    $kode_kategori = $db->getOne($sql);
    $sql_skrd = "SELECT no_skrd AS no_skrd FROM app_skrd WHERE id_skrd='$id_skrd'";
    $no_skrd = $db->getOne($sql_skrd);
    $stamp2    = date("His");
    $len = 2;
    $base = '123456789';
    $max = strlen($base) - 1;
    $activatecode = '';

    mt_srand((float)microtime() * 1000000);

    while (strlen($activatecode) < $len) {
        $activatecode .= $base{
            mt_rand(0, $max)};
    }
    $billing_code = $kode_kategori . $activatecode . $stamp2 . $no_skrd;

    return $billing_code;
}
