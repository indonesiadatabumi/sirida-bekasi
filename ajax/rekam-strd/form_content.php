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
<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg ">
		<div class="modal-content">
		</div>
	</div>
</div>
<!-- END MODAL -->

<form action="ajax/rekam-strd/insert_data.php" method="post" class="form-horizontal">
	<input type="hidden" name="id_skrd" id="id_skrd">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="bulan_realisasi">Realisasi</label>
				<div class="col-md-6">
					<select class="form-control" name="bulan_realisasi" id="bulan_realisasi">
						<option value="" selected disabled>-- Pilih Bulan Realisasi --</option>
						<option value="01">Januari</option>
						<option value="02">Februari</option>
						<option value="03">Maret</option>
						<option value="04">April</option>
						<option value="05">Mei</option>
						<option value="06">Juni</option>
						<option value="07">Juli</option>
						<option value="08">Agustus</option>
						<option value="09">September</option>
						<option value="10">Oktober</option>
						<option value="11">November</option>
						<option value="12">Desember</option>
					</select>
				</div>
				<div class="col-md-4">
					<select class="form-control" name="tahun_realisasi" id="tahun_realisasi">
						<option value="" selected disabled>-- Pilih Tahun Realisasi --</option>
						<option value="2025">2025</option>
						<option value="2024">2024</option>
						<option value="2023">2023</option>
						<option value="2022">2022</option>
					</select>
				</div>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Nomor SKRD</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="no_skrd" id="no_skrd">
					</div>
				</div>

				<div class="col-md-2">
					<button type="button" class="btn btn-default" onclick="pop_up_strd()"><i class="fa fa-search"></i></button>
				</div>

			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="jenis_retribusi">Jenis Retribusi</label>
				<div class="col-md-10">
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

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">NPWRD</label>
				<div class="col-md-10">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="npwrd" id="npwrd" readonly>
					</div>
				</div>

			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">No. Reg STRD</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="strd_nomor" id="strd_nomor">
					</div>
				</div>
				<div class="col-md-2">
					<button type="button" class="btn btn-default" onclick="get_nomor_strd()"><i class="fa fa-refresh"></i></button>
				</div>

			</div>
		</div>

		<div class=" col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Nama WR</label>
				<div class="col-md-10">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="wp_wr_nama" id="wp_wr_nama" readonly>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Tgl Proses</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="date" class="form-control" name="tgl_proses" value="<?= date('Y-m-d') ?>">
					</div>
				</div>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Alamat</label>
				<div class="col-md-10">
					<div class="icon-addon addon-md">
						<textarea class="form-control" name="wp_wr_alamat" id="wp_wr_alamat" readonly></textarea>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Periode</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="periode" id="periode" value="<?= date('Y') ?>">
					</div>
				</div>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Masa Pajak</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="bln_retribusi" id="bln_retribusi" readonly>
					</div>
				</div>

				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="thn_retribusi" id="thn_retribusi" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Tgl Penetapan</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="tgl_penetapan" id="tgl_penetapan" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Tgl Jatuh Tempo</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="tgl_jatuh_tempo" id="tgl_jatuh_tempo" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Tgl Setoran</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="tgl_setoran" id="tgl_setoran" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Jumlah Setoran / Tagihan</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="jumlah_setoran" id="jumlah_setoran" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Bulan Pengenaan</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="bulan_pengenaan" id="bulan_pengenaan" readonly>
					</div>
				</div>

				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="tarif" id="tarif" value="1%" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="form-group">

				<label class="control-label col-md-2" for="prepend">Pajak Terhutang</label>
				<div class="col-md-4">
					<div class="icon-addon addon-md">
						<input type="text" class="form-control" name="pajak_terhutang" id="pajak_terhutang" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="form-actions">
		<div class="row">
			<div class="col-md-12">
				<button class="btn btn-default" type="reset">
					Reset
				</button>
				<button class="btn btn-primary" type="submit">
					<i class="fa fa-save"></i>
					Submit
				</button>
			</div>
		</div>
	</div>
</form>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	$('#no_skrd').on('keydown', function(event) {
		if (event.key === 'Enter') {
			event.preventDefault(); // Biar gak submit form kalau ada form
			get_calon_strd(); // Panggil fungsi lu
		}
	});

	function get_nomor_strd() {
		const jenis_retribusi = $('#jenis_retribusi').val();
		const periode = $('#periode').val();
		$.ajax({
			type: "POST",
			url: "ajax/rekam-strd/get_nomor_strd.php",
			data: {
				'jenis_retribusi': jenis_retribusi,
				'periode': periode
			},
			dataType: "json",
			success: function(response) {
				if (response.response_code == '00') {
					$('#strd_nomor').val(response.data);
				} else {
					alert('gagal')
				}
			}
		});
	}

	function pilihSKRD(id_skrd) {
		get_calon_strd(id_skrd); // Panggil AJAX
		$('#remoteModal').modal('hide'); // Tutup modal
	}

	function get_calon_strd(id_skrd = '') {
		const jenis_retribusi = $('#jenis_retribusi').val();
		const tahun_realisasi = $('#tahun_realisasi').val();
		const no_skrd = $('#no_skrd').val();
		let data_response = ''
		$.ajax({
			type: "POST",
			url: "ajax/rekam-strd/get_calon_strd.php",
			data: {
				'jenis_retribusi': jenis_retribusi,
				'tahun_realisasi': tahun_realisasi,
				'no_skrd': no_skrd,
				'id_skrd': id_skrd
			},
			dataType: "json",
			success: function(response) {
				data_response = response.data
				if (response.response_code == '00') {

					$('#id_skrd').val(data_response.id_skrd);
					$('#npwrd').val(data_response.npwrd);
					$('#no_skrd').val(data_response.no_skrd);
					$('#wp_wr_nama').val(data_response.wp_wr_nama);
					$('#wp_wr_alamat').val(data_response.wp_wr_alamat);
					$('#bln_retribusi').val(data_response.bln_retribusi);
					$('#thn_retribusi').val(data_response.thn_retribusi);
					$('#tgl_penetapan').val(data_response.tgl_penetapan);
					$('#tgl_jatuh_tempo').val(data_response.tgl_jatuh_tempo);
					$('#tgl_setoran').val(data_response.tgl_setoran);
					$('#jumlah_setoran').val(parseFloat(data_response.jumlah_setoran).toLocaleString('id-ID'));
					$('#bulan_pengenaan').val(data_response.bulan_pengenaan);

					hitung_pajak()
				} else {
					alert(response.response_message)
				}
			}
		});
	}

	function pop_up_strd() {
		const jenis_retribusi = $('#jenis_retribusi').val();
		const bulan_realisasi = $('#bulan_realisasi').val();
		const tahun_realisasi = $('#tahun_realisasi').val();

		// Cek validasi jika perlu
		if (!jenis_retribusi || !bulan_realisasi || !tahun_realisasi) {
			alert('Lengkapi semua input sebelum menampilkan data!');
			return;
		}

		// Tampilkan loading atau kosongkan konten dulu
		$('#remoteModal .modal-content').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading...</div>');

		// Buka modal duluan
		$('#remoteModal').modal('show');

		// Panggil AJAX untuk isi modal
		$.ajax({
			type: "POST",
			url: "ajax/rekam-strd/modal_content.php",
			data: {
				'jenis_retribusi': jenis_retribusi,
				'bulan_realisasi': bulan_realisasi,
				'tahun_realisasi': tahun_realisasi
			},
			success: function(html) {
				// Langsung render konten HTML ke modal
				$('#remoteModal .modal-content').html(html);
				$('#data_strd').DataTable({
					"pageLength": 10,
					"lengthChange": false,
					"ordering": false
				});
			},
			error: function(xhr, status, error) {
				$('#remoteModal .modal-content').html('<div class="text-danger p-4">Gagal memuat data: ' + error + '</div>');
			}
		});
	}

	function hitung_pajak() {
		const bulanPengenaan = parseInt($('#bulan_pengenaan').val()) || 0;

		// Ambil nilai dari input yang sudah diformat (misal: 1.000.000)
		const jumlahSetoranRaw = $('#jumlah_setoran').val();

		// Hapus titik (.) ribuan dan ganti koma (,) jadi titik (jaga-jaga)
		const jumlahSetoran = parseFloat(jumlahSetoranRaw.replace(/\./g, '').replace(',', '.')) || 0;

		const pajakPerBulan = jumlahSetoran * 0.01;
		const totalPajak = pajakPerBulan * bulanPengenaan;

		// Format total pajak sebagai angka ribuan dan masukkan ke input
		$('#pajak_terhutang').val(totalPajak.toLocaleString('id-ID'));

	}

	$('form').on('submit', function(e) {
		e.preventDefault();

		// Disable tombol submit
		const $submitBtn = $(this).find('button[type="submit"]');
		$submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.response_code === '00') {
					alert('Data berhasil disimpan!');
					// reset form kalau perlu
					$('form')[0].reset();
					$('#jumlah_setoran').val('');
					$('#pajak_terhutang').val('');
				} else {
					alert('Gagal menyimpan data: ' + response.response_message);
				}
			},
			error: function() {
				alert('Terjadi kesalahan saat menyimpan data.');
			},
			complete: function() {
				// Aktifkan kembali tombol submit
				$submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Submit');
			}
		});
	});
</script>