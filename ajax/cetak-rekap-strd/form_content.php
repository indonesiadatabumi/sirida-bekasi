<?php
require_once("inc/init.php");
require_once("../lib/DML.php");
require_once("../lib/global_obj.php");
require_once("../helpers/date_helper.php");

// $global = new global_obj($db);
// $DML1 = new DML('app_reg_wr', $db);
// $DML2 = new DML('app_ref_instansi', $db);
$DML = new DML('app_ref_jenis_retribusi', $db);
$fn = $_CONTENT_FOLDER_NAME[39];

// $act = $_GET['act'];
// $fn = $_GET['fn'];
// $men_id = $_GET['men_id'];
?>

<form id="cetak_rekap_strd" action="ajax/cetak-rekap-strd/print_rekap_strd.php" method="post" class="form-horizontal" target="_blank">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-4" for="jenis_retribusi">Jenis Retribusi</label>
				<div class="col-md-8">
					<select class="form-control" name="jenis_retribusi" id="jenis_retribusi" onchange="get_nomor_strd()">
						<option value="" selected disabled>-- Pilih Jenis Retribusi --</option>
						<?php

						$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' ORDER BY id_jenis_retribusi ASC";
						$result1 = $db->Execute($sql);

						while ($row1 = $result1->FetchRow()) {
							echo "<optgroup label='" . $row1['jenis_retribusi'] . "'>";


							$sql = "SELECT * FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '" . $row1['kd_rekening'] . "%' AND length(kd_rekening)>5 ORDER BY id_jenis_retribusi ASC";
							$result2 = $db->Execute($sql);

							while ($row2 = $result2->FetchRow()) {
								$selected = ($act == 'edit' ? (substr($row2['kd_rekening'], 0, 5) == $curr_data['kd_rekening'] ? 'selected' : '') : '');
								echo "<option value='" . $row2['kd_rekening'] . "' " . $selected . ">" . $row2['jenis_retribusi'] . "</option>";
							}

							echo "</optgroup>";
						}

						?>
					</select>
				</div>

			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-4" for="prepend">Periode</label>
				<div class="col-md-3">
					<div class="icon-addon addon-md">
						<input type="date" class="form-control" name="periode_awal" value="<?= date('Y-m-d') ?>">
					</div>
				</div>
				<div class="col-sm-2 text-center">
					S / D
				</div>
				<div class="col-md-3">
					<div class="icon-addon addon-md">
						<input type="date" class="form-control" name="periode_akhir" value="<?= date('Y-m-d') ?>">
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="form-actions text-center">
		<div class="row">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary" target="_blank">
					<i class="fa fa-print"></i> Cetak
				</button>
			</div>
		</div>
	</div>
</form>



<script>
	// $('#cetak_rekap_strd').on('submit', function(e) {
	// 	e.preventDefault();

	// 	// Disable tombol submit
	// 	const $submitBtn = $(this).find('button[type="submit"]');
	// 	$submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

	// 	$.ajax({
	// 		url: $(this).attr('action'),
	// 		type: $(this).attr('method'),
	// 		data: $(this).serialize(),
	// 		dataType: 'json',
	// 		success: function(response) {
	// 			if (response.response_code === '00') {
	// 				alert('Data berhasil disimpan!');
	// 				// reset form kalau perlu
	// 				$('form')[0].reset();
	// 				$('#jumlah_setoran').val('');
	// 				$('#pajak_terhutang').val('');
	// 			} else {
	// 				alert('Gagal menyimpan data: ' + response.response_message);
	// 			}
	// 		},
	// 		error: function() {
	// 			alert('Terjadi kesalahan saat menyimpan data.');
	// 		},
	// 		complete: function() {
	// 			// Aktifkan kembali tombol submit
	// 			$submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Submit');
	// 		}
	// 	});
	// });
</script>