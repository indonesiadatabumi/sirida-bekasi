<?php
require_once("inc/init.php");
require_once("function.php");

$columns = [
    'npwrd',
    'wp_wr_nama',
    'jenis_retribusi',
    'bln_retribusi',
    'periode',
    'jumlah_tagihan'
];

// Paging
$limit = $_POST['length'];
$offset = $_POST['start'];
$search = $_POST['search']['value'];

$sql_base = "SELECT a.strd_id, a.strd_periode, a.strd_pajak, b.npwrd, b.wp_wr_nama, b.nm_rekening, b.bln_retribusi
	FROM app_strd a 
	LEFT JOIN app_skrd b ON a.strd_skrd_id=b.id_skrd
	WHERE 1=1";

if (!empty($search)) {
    $search = pg_escape_string($search);
    $sql_base .= " AND (
		b.npwrd ILIKE '%$search%' OR 
		b.wp_wr_nama ILIKE '%$search%' OR 
		b.jenis_retribusi ILIKE '%$search%'
	)";
}

$sql_count = "SELECT COUNT(*) FROM ($sql_base) AS sub";
$totalFiltered = $db->getOne($sql_count);

$sql_final = $sql_base . " ORDER BY a.strd_id DESC LIMIT $limit OFFSET $offset";

$data_result = $db->GetAll($sql_final);

$data = [];
$no = $offset + 1;

foreach ($data_result as $row) {
    $aksi = "<a href='ajax/cetak-strd/print_strd.php?strd_id={$row['strd_id']}' target='_blank' class='btn btn-sm btn-primary'>Cetak</a>";
    $bln_retribusi = nama_bulan($row['bln_retribusi']);
    $data[] = [
        'no' => $no++,
        'npwrd' => $row['npwrd'],
        'wp_wr_nama' => $row['wp_wr_nama'],
        'jenis_retribusi' => $row['nm_rekening'],
        'bln_retribusi' => $bln_retribusi,
        'periode' => $row['strd_periode'],
        'jumlah_tagihan' => $row['strd_pajak'],
        'aksi' => $aksi
    ];
}

$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalFiltered,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
