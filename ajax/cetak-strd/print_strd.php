<?php
require_once("inc/init.php");

$strd_id = $_GET['strd_id'];

$sql = "SELECT a.strd_id, a.strd_jatuh_tempo, a.strd_periode, a.strd_nomor, a.strd_pajak, a.strd_kode_billing, b.*
	FROM app_strd a 
	LEFT JOIN app_skrd b ON a.strd_skrd_id=b.id_skrd
	WHERE strd_id = $strd_id";
$result1 = $db->Execute($sql);
$row = $result1->fields;
$wp_wr_alamat = $row['wp_wr_alamat'] . " Kel. " . $row['wp_wr_lurah'] . " Kec. " . $row['wp_wr_camat'] . " " . $row['wp_wr_kabupaten'];

// Variabel bantuan
$jumlah = number_format($row['strd_pajak'], 0, ',', '.');
$hitung_denda = $row['strd_pajak'] * 0.016;
$denda = number_format($hitung_denda, 0, ',', '.');
$hitung_total_bayar = $row['strd_pajak'] + $denda;
$total_bayar = number_format($hitung_total_bayar, 0, ',', '.');
$jumlah_terbilang = ucwords(terbilang($hitung_total_bayar)) . " Rupiah";
$tgl_cetak = date('d-m-Y');
$next_nomor = (int)$row['strd_nomor'];
$strd_nomor = str_pad($next_nomor, 4, '0', STR_PAD_LEFT);

// Fungsi terbilang
function terbilang($angka)
{
    $angka = (float)$angka;
    $bilangan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    if ($angka < 12) return " " . $bilangan[$angka];
    elseif ($angka < 20) return terbilang($angka - 10) . " Belas";
    elseif ($angka < 100) return terbilang($angka / 10) . " Puluh" . terbilang(fmod($angka, 10));
    elseif ($angka < 200) return " Seratus" . terbilang($angka - 100);
    elseif ($angka < 1000) return terbilang($angka / 100) . " Ratus" . terbilang(fmod($angka, 100));
    elseif ($angka < 2000) return " Seribu" . terbilang($angka - 1000);
    elseif ($angka < 1000000) return terbilang($angka / 1000) . " Ribu" . terbilang(fmod($angka, 1000));
    else return "Angka terlalu besar";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>STRD Cetak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 40px;
            border: 1px solid #000;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #000;
            padding: 4px;
        }

        .signature {
            margin-top: 40px;
            margin-left: 300px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
        }

        .cut-line {
            margin-top: 40px;
            border-top: 1px dashed #000;
            text-align: center;
            font-size: 10px;
        }

        .logo {
            padding-left: 30px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td><img src="logo_bekasi.png" alt="" class="logo" width="70px">
            </td>
            <td>
                <h3 class="center">STRD (SURAT TAGIHAN RETRIBUSI DAERAH)</h3>
            </td>
            <td>
                <p class="right">No. Urut: <?= $strd_nomor ?></p>
            </td>
        </tr>
    </table>
    <hr>
    <table>
        <tr>
            <td>Nama</td>
            <td>: <?= $row['wp_wr_nama'] ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= $wp_wr_alamat ?></td>
        </tr>
        <tr>
            <td>NPWRD</td>
            <td>: <?= $row['npwrd'] ?></td>
        </tr>
        <tr>
            <td>Tanggal Jatuh Tempo</td>
            <td>: <?= date('d M Y', strtotime($row['strd_jatuh_tempo'])) ?></td>
        </tr>
        <tr>
            <td>Kode Bayar</td>
            <td>: <?= $row['strd_kode_billing'] ?></td>
        </tr>
    </table>

    <br>
    <p><strong>I. </strong>Berdasarkan Pasal 158 Peraturan Daerah Kota Bekasi No. 1 Tahun 2024 telah dilakukan penelitian dan atau pemeriksaan atau keterangan lain atas pelaksanaan kewajiban : <br>
        Kode Rekening : <br>
        Nama Retribusi : <?= $row['nm_rekening'] ?></p>
    <p><strong>II. </strong>Dan penelitian dan atau pemeriksaan tersebut diatas, penghitungan jumlah yang masih harus dibayar adalah sebagai berikut :</p>
    <table class="bordered">
        <tr>
            <th>No</th>
            <th>Uraian</th>
            <th>Jumlah (Rp)</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Pajak yang kurang bayar </td>
            <td class="right">0</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Sanksi Administratif (Psl. 158 ayat (5))</td>
            <td class="right"><?= $jumlah ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Denda Pembayaran</td>
            <td class="right"><strong><?= $denda ?></strong></td>
        </tr>
        <tr>
            <td>4</td>
            <td><strong>Total</strong></td>
            <td class="right"><strong><?= $total_bayar ?></strong></td>
        </tr>
    </table>

    <p>Dengan huruf: <em><strong><?= $jumlah_terbilang ?></strong></em></p>

    <p><strong>PERHATIAN:</strong></p>
    <ol>
        <li>Harap penyetoran dilakukan melalui Bank yang ditunjuk.</li>
        <li>Apabila lewat 30 hari dari tanggal jatuh tempo dikenakan denda 1,6% per bulan.</li>
    </ol>

    <div class="signature">
        <table>
            <tr>
                <td></td>
                <td></td>
                <td align="center">
                    Bekasi, <?= date('d M Y') ?><br>
                    a.n. Kepala Bapenda<br>
                    Kabid Pengawasan dan Pengendalian Pendapatan<br><br><br><br><br>
                    <strong>Robbie Arifansyah Putra, S.STP, M.M.</strong><br>
                    Penata Tk. I<br>
                    NIP. 19850419 200312 1 002
                </td>
            </tr>
        </table>
    </div>

    <div class="cut-line">Gunting di sini ------------------------------------------------------------</div>

    <h4 class="center">TANDA TERIMA</h4>
    <table>
        <tr>
            <td>No. STRD</td>
            <td>: <?= $strd_nomor ?></td>
        </tr>
        <tr>
            <td>NPWRD</td>
            <td>: <?= $row['npwrd'] ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: <?= $row['wp_wr_nama'] ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= $wp_wr_alamat ?></td>
        </tr>
    </table>

    <br>
    <div style="margin-top:40px;">
        <table>
            <tr>
                <td width="40%"></td>
                <td width="20%"></td>
                <td align="center">
                    Bekasi, ...................................<br><br><br><br><br>
                    (..........................................)<br>
                    Yang Menerima
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">Printed on <?= $tgl_cetak ?> from SIRIDA</div>

    <script>
        window.print();
    </script>

</body>

</html>