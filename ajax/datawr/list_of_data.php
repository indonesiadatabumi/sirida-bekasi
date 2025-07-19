<?php
if ($readAccess) {
	$global = new global_obj($db);
	$ip = get_ip();
	$global->log_akses($ip, 'Data Wajib REtribusi ');
	/*
	echo '
		<select id="categoryFilter" class="form-control">
			<option value="">Show All</option>
			<option value="RAWALUMBU">RAWALUMBU</option>
			<option value="PONDOK GEDE">PONDOK GEDE</option>
			<option value="BEKASI TIMUR">BEKASI TIMUR</option>
		</select>
		';
	*/

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
} else {
	echo "
	<div class='alert alert-warning fade in'>
		<i class='fa-fw fa fa-warning'></i>
		Anda tidak memiliki hak akses untuk melihat data !
	</div>";
}
