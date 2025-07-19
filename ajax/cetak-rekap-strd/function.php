<?php
function format_periode($periode_awal, $periode_akhir)
{
    // Pastikan dalam format Y-m-d
    $awal = date_create_from_format('Y-m-d', $periode_awal);
    $akhir = date_create_from_format('Y-m-d', $periode_akhir);

    // Jika format salah atau tanggal tidak valid
    if (!$awal || !$akhir) return '-';

    // Nama bulan Indonesia
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $tgl_awal = $awal->format('d') . ' ' . $bulan[(int)$awal->format('m')] . ' ' . $awal->format('Y');
    $tgl_akhir = $akhir->format('d') . ' ' . $bulan[(int)$akhir->format('m')] . ' ' . $akhir->format('Y');

    return $tgl_awal . ' s.d ' . $tgl_akhir;
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
