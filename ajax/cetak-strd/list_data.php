<?php
require_once("inc/init.php");
require_once("../lib/DML.php");
require_once("../lib/global_obj.php");
require_once("../helpers/date_helper.php");

// $global = new global_obj($db);
// $DML1 = new DML('app_reg_wr', $db);
// $DML2 = new DML('app_ref_instansi', $db);
$DML = new DML('app_ref_jenis_retribusi', $db);
$fn = $_CONTENT_FOLDER_NAME[40];

// $act = $_GET['act'];
// $fn = $_GET['fn'];
// $men_id = $_GET['men_id'];
?>

<div id="dt_basic_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
	<table id="data_strd" class="table table-striped table-bordered table-hover dataTable no-footer" width="100%" role="grid" aria-describedby="dt_basic_info" style="width: 100%;">
		<thead>
			<tr role="row">
				<th>No</th>
				<th>NPWRD</th>
				<th>Nama WR</th>
				<th>Jenis Retribusi</th>
				<th>Bulan Retribusi</th>
				<th>Periode</th>
				<th>Jumlah Tagihan</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>

<script>
	function pagefunction() {
		var table = $('#data_strd').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "ajax/cetak-strd/get_data_strd.php",
				"type": "POST"
			},
			"columns": [{
					"data": "no"
				},
				{
					"data": "npwrd"
				},
				{
					"data": "wp_wr_nama"
				},
				{
					"data": "jenis_retribusi"
				},
				{
					"data": "bln_retribusi"
				},
				{
					"data": "periode"
				},
				{
					"data": "jumlah_tagihan",
					"render": $.fn.dataTable.render.number(',', '.', 0, 'Rp ')
				},
				{
					"data": "aksi",
					"orderable": false,
					"searchable": false
				}
			]
		});
	}

	// load related plugins

	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function() {
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function() {
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function() {
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function() {
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
</script>