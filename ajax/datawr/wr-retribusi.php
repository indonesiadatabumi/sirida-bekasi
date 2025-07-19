<?php
$start_time = microtime(true);
require_once("inc/init.php");

$kec = strtoupper($_POST['kec']);
$key = strtoupper($_POST['key']);
$ret = $_POST['ret'];

if ($kec) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.kecamatan='$kec'";
}

if ($ret) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.kd_rekening='$ret'";
}

if ($key) {
    $rel = (preg_match("#where#", $cond)) ? "and" : "where";
    $cond .= " $rel a.nm_wp_wr like '%$key%'";
}

$list_sql = "SELECT a.npwrd,a.nm_wp_wr,a.alamat_wp_wr,a.no_tlp,a.kelurahan,a.kecamatan,a.kota,b.jenis_retribusi FROM public.app_reg_wr as a 
			LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_rekening=b.kd_rekening)
            $cond 
            ORDER BY a.npwrd ";

$list_of_data = $db->Execute($list_sql);
if (!$list_of_data)
    print $db->ErrorMsg();

echo "
<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
    <thead>
        <tr>
            <th width='4%'>No.</th>
            <th>NPWRD</th>				
            <th>Wajib Retribusi</th>				
            <th>Alamat WR</th>
            <th>Kecamatan</th>
            <th>Jenis Retribusi</th>				
        </tr>
    </thead>
    <tbody>";
$no = 0;
while ($row = $list_of_data->FetchRow()) {
    foreach ($row as $key => $val) {
        $key = strtolower($key);
        $$key = $val;
    }
    $no++;
    echo "
            <tr><td align='center'>" . $no . "</td>
            <td>" . $npwrd . "</td>				
            <td>" . $nm_wp_wr . "</td>
            <td>" . $alamat_wp_wr . ", Kel. " . $kelurahan . "</td>				
            <td>" . $kecamatan . "</td>
            <td>" . $jenis_retribusi . "</td>				
            </tr>";
}
echo "</tbody>
</table>";

echo "<div style='text-align: center;'>page load in " . number_format(microtime(true) - $start_time, 2) . " seconds.</div>";


// include("inc/scripts.php");
?>

<script>
    $('#data-table-jq').dataTable({
        searching: false,
        paging: true,
        info: true,
        pageLength: 50,
    });
</script>