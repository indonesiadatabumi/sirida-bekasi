<?php
require_once("inc/init.php");
require_once("function.php");

$jenis_retribusi = $_POST['jenis_retribusi'];
$bulan_realisasi = $_POST['bulan_realisasi'];
$tahun_realisasi = $_POST['tahun_realisasi'];

$sql = "SELECT a.*, b.tgl_pembayaran, (a.tgl_penetapan + INTERVAL '30 days') AS tgl_jatuh_tempo
        FROM app_skrd a 
        LEFT JOIN app_pembayaran_retribusi b ON a.kd_billing = b.kd_billing 
        WHERE a.kd_rekening = '$jenis_retribusi' 
        AND EXTRACT(MONTH FROM b.tgl_pembayaran) = '$bulan_realisasi' 
        AND EXTRACT(YEAR FROM b.tgl_pembayaran) = '$tahun_realisasi'
        AND b.tgl_pembayaran > (a.tgl_penetapan + INTERVAL '30 days')";
$result1 = $db->Execute($sql);
$data = $result1->fields;

// tampil data
echo '
<div class="modal-header">
	<h4 class="modal-title">Daftar WR SKRD</h4>
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
</div>
<div class="modal-body">
	<table id="data_strd" class="table table-striped table-bordered table-hover dataTable no-footer" width="100%" role="grid" aria-describedby="dt_basic_info" style="width: 100%;">
		<thead>
			<tr role="row">
				<th>No</th>
				<th>NPWRD</th>
				<th>Nama WR</th>
                <th>No SKRD</th>
				<th>Tanggal Penetapan</th>
				<th>Tanggal Jatuh Tempo</th>
				<th>Tanggal Pembayaran</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>';
$no = 1;
while (!$result1->EOF) {
    $row = $result1->fields;

    $npwrd = $row['npwrd'];
    $nama_wr = $row['wp_wr_nama'];
    $no_skrd = $row['no_skrd'];
    $tgl_penetapan = date('d-m-Y', strtotime($row['tgl_penetapan']));
    $tgl_jatuh_tempo = date('d-m-Y', strtotime($row['tgl_jatuh_tempo']));
    $tgl_pembayaran = date('d-m-Y', strtotime($row['tgl_pembayaran']));
    $id_skrd = $row['id_skrd']; // atau sesuaikan primary key-nya

    echo "<tr>
		<td>{$no}</td>
		<td>{$npwrd}</td>
		<td>{$nama_wr}</td>
        <td>{$no_skrd}</td>
		<td>{$tgl_penetapan}</td>
		<td>{$tgl_jatuh_tempo}</td>
		<td>{$tgl_pembayaran}</td>
		<td><button type='button' onclick='pilihSKRD(\"{$id_skrd}\")' ><i class='fa fa-check'></i></button></td>
	</tr>";

    $no++;
    $result1->MoveNext();
}

echo '</tbody></table></div>';
