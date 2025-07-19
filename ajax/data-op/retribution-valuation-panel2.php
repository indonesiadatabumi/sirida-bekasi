<?php

	$display1 = 'none';
	$display2 = 'none';	

	if($kd_rekening!='')
	{
		if($kd_rekening==$korek_imb)
		{
		//	$display1 = ($thn_dasar_pengenaan=='' || $thn_dasar_pengenaan=='2012'?'block':'none');
		//	$display2 = ($thn_dasar_pengenaan=='2017'?'block':'none');
		
		
			$display2 = ($thn_dasar_pengenaan=='' || $thn_dasar_pengenaan=='2017'?'block':'none');
			$display1 = ($thn_dasar_pengenaan=='2012'?'block':'none');
		}
	}
	
	$prefix = 'imb17_';
	echo "
	<div class='row' id='retribution-valuation-panel2' style='display:".$display2."'>
		<div class='col col-md-12'>
			<section>
				<div class='row'>
					<label class='label col col-3'>Jenis/Tipe Bangunan<font color='red'>*</font></label>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='".$prefix."jenis_bangunan' id='".$prefix."jenis_bangunan' class='form-control' value='".($act=='edit'?$curr_data['jenis_bangunan']:'Rumah Tinggal')."' required/>
						</label>								
					</div>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='".$prefix."tipe_bangunan' id='".$prefix."tipe_bangunan' class='form-control' value='".($act=='edit'?$curr_data['tipe_bangunan']:'P.II')."'/>
						</label>								
					</div>
				</div>
			</section>
			<br />
			<table width='100%' border=0>
				<tr>
				<td valign='top' width='50%'>
					<table class='table table-striped' width='100%'>
						<thead>
							<tr>
								<td rowspan='2' align='center'></td>
								<td rowspan='2' align='center'>Bangunan</td>
								<td rowspan='2' width='20%' align='center'>Luas (m<sup>2</sup>)</td>
								<td align='center'>Indeks</td>
							</tr>
							<tr>
								<td align='center'>Prasarana</td>
							</tr>
						</thead>
						<tbody id='valuation2_2_1-tbody'>";

							$valuation2_rows = array(
														array('id_rincian_bangunan'=>'','bangunan'=>'','luas'=>''),														
													);
							$grand = 0;

							if($act=='edit')
							{
								$valuation2_rows = array();
								$sql = "SELECT * FROM app_rincian_bangunan_imb2017 WHERE(fk_nota='".$curr_data['id_nota']."')";
								$result = $db->Execute($sql);
								if(!$result)
									echo $db->ErrorMsg();

								while($row = $result->FetchRow())
								{
									$valuation2_rows[] = array('id_rincian_bangunan'=>$row['id_rincian_bangunan'],
										'bangunan'=>$row['bangunan'],'luas'=>number_format($row['luas'],2,'.',','));
								}
							}

							$i = 0;

							foreach($valuation2_rows as $row)
							{
								$i++;
								echo "
								<tr id='row-".$i."'>
									<td>";
									if($i>1)
									{
										echo "<button type='button' id='panel2_2_1_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_row_panel2_2_1('".$i."');\"><i class='fa fa-trash-o'></i></button>";
									}
									echo "</td>
									<td>
									<input type='hidden' name='".$prefix."id_rincian_bangunan".$i."' id='".$prefix."id_rincian_bangunan".$i."' value='".$row['id_rincian_bangunan']."'/>
									<input type='text' name='".$prefix."bangunan".$i."' id='".$prefix."bangunan".$i."' style='width:100%;' value='".$row['bangunan']."' required/></td>
									<td><input type='text' class='thousand_format2' name='".$prefix."luas_bangunan".$i."' id='".$prefix."luas_bangunan".$i."' style='width:100%;text-align:right;' 
									value='".$row['luas']."' onkeyup=\"mix_panel2_2_function1()\" required/></td>
									<td></td>
								</tr>";
							}

						echo "</tbody>
						<tfoot>
							<tr>
								<td colspan='4' align='center'>
								<a href='javascript:;' onclick=\"add_valuation_table_row2_2_1();\"><i class='fa fa-plus'></i> Tambah Baris</a>
								</td>
							</tr>
							<tr>
								
								<td colspan='2' align='right'><b>LUAS SELURUH BANGUNAN <sup><font color='red'>(1)</font>, <font color='red'>(2)</font></sup></b></td>
								<td width='10%'>
									<input type='text' name='".$prefix."total_luas_bangunan' id='".$prefix."total_luas_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['total_luas_bangunan'],2,'.',','):'')."' class='autofill-bg' readonly/>
								</td>
								<td width='10%'>
									<input type='text' class='decimal' name='".$prefix."indeks_prasarana' id='".$prefix."indeks_prasarana' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_prasarana'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function2();\" required/>
								</td>
							</tr>
						</tfoot>
					</table>
					<input type='hidden' id='".$prefix."n_valuation_row2_2_1' name='".$prefix."n_valuation_row2_2_1' value='".$i."'/>
				</td>
				<td valign='top' width='50%'>
					<table class='table table-striped' width='100%'>
						<thead>
							<tr>
								<td colspan='4' align='center'>Indeks Parameter Klasifikasi Bangunan Gedung</td>
							</tr>
							<tr>
								<td align='center'>Parameter</td>
								<td width='15%' align='center'>Bobot</td>
								<td width='15%' align='center'>Indeks</td>
								<td width='15%' align='center'>Nilai</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Kompleksitas</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_kompleksitas' id='".$prefix."bobot_kompleksitas' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_kompleksitas'],2,'.',','):'0.25')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_kompleksitas' id='".$prefix."indeks_kompleksitas' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_kompleksitas'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_kompleksitas' id='".$prefix."nilai_kompleksitas' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
							<tr>
								<td>Permanensi</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_permanensi' id='".$prefix."bobot_permanensi' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_permanensi'],2,'.',','):'0.20')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_permanensi' id='".$prefix."indeks_permanensi' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_permanensi'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_permanensi' id='".$prefix."nilai_permanensi' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
							<tr>
								<td>Resiko Kebakaran</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_resiko_kebakaran' id='".$prefix."bobot_resiko_kebakaran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_resiko_kebakaran'],2,'.',','):'0.15')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_resiko_kebakaran' id='".$prefix."indeks_resiko_kebakaran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_resiko_kebakaran'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_resiko_kebakaran' id='".$prefix."nilai_resiko_kebakaran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
							<tr>
								<td>Zonasi Gempa</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_zonasi_gempa' id='".$prefix."bobot_zonasi_gempa' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_zonasi_gempa'],2,'.',','):'0.15')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_zonasi_gempa' id='".$prefix."indeks_zonasi_gempa' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_zonasi_gempa'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_zonasi_gempa' id='".$prefix."nilai_zonasi_gempa' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
							<tr>
								<td>Ketinggian Bangunan</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_ketinggian_bangunan' id='".$prefix."bobot_ketinggian_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_ketinggian_bangunan'],2,'.',','):'0.10')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_ketinggian_bangunan' id='".$prefix."indeks_ketinggian_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_ketinggian_bangunan'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_ketinggian_bangunan' id='".$prefix."nilai_ketinggian_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
							<tr>
								<td>Kepemilikan Bangunan</td>
								<td><input type='text' class='decimal' name='".$prefix."bobot_kepemilikan_bangunan' id='".$prefix."bobot_kepemilikan_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['bobot_kepemilikan_bangunan'],2,'.',','):'0.05')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_kepemilikan_bangunan' id='".$prefix."indeks_kepemilikan_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_kepemilikan_bangunan'],2,'.',','):'')."' onkeyup=\"mix_panel2_2_function3();\" required/></td>
								<td><input type='text' name='".$prefix."nilai_kepemilikan_bangunan' id='".$prefix."nilai_kepemilikan_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_kompleksitas'],2,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan='3' align='right'><b>TOTAL <sup><font color='red'>(3)</font></sup></b></td>
								<td>
									<input type='hidden' name='".$prefix."id_indeks' value='".($act=='edit'?$curr_data['id_indeks']:'')."'/>
									<input type='text' name='".$prefix."total_nilai_indeks_terintegrasi' id='".$prefix."total_nilai_indeks_terintegrasi' class='autofill-bg' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['total_nilai_indeks_terintegrasi'],2,'.',','):'')."' readonly/>
								</td>
							</tr>
						</tfoot>
					</table>
				</td>
				</tr>
				<tr>
					<td colspan='2' valign='top'>
						<table class='table table-striped' width='100%'>
							<thead>
								<tr>
									<td rowspan='2'></td>
									<td align='center' colspan='3'>Indeks <sup><font color='red'>(4)</font></sup></td>
									<td rowspan='2' align='center'>Harga Satuan Retribusi (Rp.)<br /><sup><font color='red'>(5)</font></sup></td>
									<td rowspan='2' align='center'>Total Retribusi Bangunan (Rp.)<br /><sup><font color='red'>(1)*(2)*(3)*(4)*(5)</font></sup>
									</td>
								</tr>
								<tr>
									<td colspan='3' align='center'>Penggunaan Gedung * Waktu Penggunaan * Bangunan Bawah Permukaan Tanah</td>
								</tr>
							</thead>
							<tbody>
								<td align='center'><b>(I)</b></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_penggunaan_gedung' id='".$prefix."indeks_penggunaan_gedung' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_penggunaan_gedung'],2,'.',','):'1.00')."' onkeyup=\"mix_panel2_2_function2()\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_waktu_penggunaan' id='".$prefix."indeks_waktu_penggunaan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_waktu_penggunaan'],2,'.',','):'1.00')."' onkeyup=\"mix_panel2_2_function2()\" required/></td>
								<td><input type='text' class='decimal' name='".$prefix."indeks_bangunan_bawah_permukaan_tanah' id='".$prefix."indeks_bangunan_bawah_permukaan_tanah' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['indeks_bangunan_bawah_permukaan_tanah'],2,'.',','):'1.00')."' onkeyup=\"mix_panel2_2_function2()\" required/></td>
								<td><input type='text' class='thousand_format1' name='".$prefix."harga_satuan_retribusi_bangunan' id='".$prefix."harga_satuan_retribusi_bangunan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['harga_satuan_retribusi_bangunan'],0,'.',','):'')."' onkeyup=\"mix_panel2_2_function2()\" required/></td>
								<td><input type='text' name='".$prefix."tot_nilai_retribusi_bangunan' id='".$prefix."tot_nilai_retribusi_bangunan' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' value='".($act=='edit'?number_format($curr_data['total_retribusi_bangunan'],0,'.',','):'')."' class='autofill-bg' readonly/></td>
							</tbody>
						</table>
					</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>


				<tr>
					<table class='table table-striped' width='100%'>
						<thead>
							<tr>
								<td></td>
								<td align='center'>Prasarana</td>
								<td width='15%' align='center'>Luas/Vol/Jumlah</td>
								<td align='center'>Satuan</td>
								<td width='10%' align='center'>Indeks Penggunaan</td>
								<td align='center'>Harga Satuan Retribusi (Rp.)</td>
								<td align='center'>Jumlah Nilai Retribusi (Rp.)</td>
								<td align='center'></td>
							</tr>							
						</thead>
						<tbody id='valuation2_2_2-tbody'>";
							$valuation2_rows = array(
														array('id_rincian_prasarana'=>'','prasarana'=>'','luas'=>'','satuan'=>'',
															  'indeks_penggunaan_prasarana'=>'','harga_satuan_retribusi_prasarana'=>'','tot_nilai_retribusi_prasarana'=>''),
													);
							$grand = 0;

							if($act=='edit')
							{
								$valuation2_rows = array();
								$sql = "SELECT * FROM app_rincian_prasarana_imb2017 WHERE(fk_nota='".$curr_data['id_nota']."')";
								$result = $db->Execute($sql);
								if(!$result)
									echo $db->ErrorMsg();

								while($row = $result->FetchRow())
								{
									$valuation2_rows[] = array('id_rincian_prasarana'=>$row['id_rincian_prasarana'],
															   'prasarana'=>$row['prasarana'],
															   'luas'=>number_format($row['luas'],2,'.',','),
															   'satuan'=>$row['satuan'],
															   'indeks_penggunaan_prasarana'=>number_format($row['indeks_penggunaan'],2,'.',','),
															   'harga_satuan_retribusi_prasarana'=>number_format($row['harga_satuan_retribusi']),
															   'tot_nilai_retribusi_prasarana'=>number_format($row['total_nilai_retribusi']));
								}
							}

							$i = 0;

							foreach($valuation2_rows as $row)
							{
								$i++;
								echo "
								<tr id='row-".$i."'>
									<td></td>
									<td>
									<input type='hidden' name='".$prefix."id_rincian_prasarana".$i."' id='".$prefix."id_rincian_prasarana".$i."' value='".$row['id_rincian_prasarana']."'/>
									<input type='text' name='".$prefix."prasarana".$i."' id='".$prefix."prasarana".$i."' style='width:100%;' value='".$row['prasarana']."' required/></td>
									<td><input type='text' class='thousand_format2' name='".$prefix."luas_prasarana".$i."' id='".$prefix."luas_prasarana".$i."' style='width:100%;text-align:right;' value='".$row['luas']."' onkeyup=\"mix_panel2_2_function4('".$i."');\" required/></td>
									<td><input type='text' name='".$prefix."satuan".$i."' id='".$prefix."satuan".$i."' value='".$row['satuan']."' required/></td>
									<td><input type='text' class='decimal' name='".$prefix."indeks_penggunaan_prasarana".$i."' id='".$prefix."indeks_penggunaan_prasarana".$i."' style='width:100%;text-align:right;' value='".$row['indeks_penggunaan_prasarana']."' onkeyup=\"mix_panel2_2_function4('".$i."');\" required/></td>
									<td><input type='text' class='thousand_format2' name='".$prefix."harga_satuan_retribusi_prasarana".$i."' id='".$prefix."harga_satuan_retribusi_prasarana".$i."' style='width:100%;text-align:right;' value='".$row['harga_satuan_retribusi_prasarana']."' onkeyup=\"mix_panel2_2_function4('".$i."');\" required/></td>
									<td><input type='text' name='".$prefix."tot_nilai_retribusi_prasarana".$i."' id='".$prefix."tot_nilai_retribusi_prasarana".$i."' style='width:100%;text-align:right;' value='".$row['tot_nilai_retribusi_prasarana']."' class='autofill-bg' readonly/></td>
									<td>";
									if($i>1)
									{
										echo "<button type='button' id='panel2_2_2_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_row_panel2_2_2('".$i."');\"><i class='fa fa-trash-o'></i></button>";
									}
									echo "</td>
								</tr>";
							}

						echo "</tbody>
						<tfoot>
							<tr>
								<td><b>(II)</b></td>
								<td colspan='2' align='left'><a href='javascript:;' onclick=\"add_valuation_table_row2_2_2();\"><i class='fa fa-plus'></i> Tambah Baris</a></td>
								<td colspan='3' align='right'><b>TOTAL RETRIBUSI PRASARANA</b></td>
								<td>
									<input type='text' name='".$prefix."grand_nilai_retribusi_prasarana' id='".$prefix."grand_nilai_retribusi_prasarana' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' value='".($act=='edit'?number_format($curr_data['total_retribusi_prasarana'],0,'.',','):'')."' class='autofill-bg' readonly/>
								</td>
							</tr>							
						</tfoot>
					</table>
					<input type='hidden' id='".$prefix."n_valuation_row2_2_2' name='".$prefix."n_valuation_row2_2_2' value='".$i."'/>
				</tr>
				<tr><td colspan='2'><br /></td></tr>
				<tr>";
					$checked = ($act=='edit'?($curr_data['imb_pengganti']=='1'?'checked':''):'');
					echo "<td colspan='2'><input type='checkbox' name='".$prefix."imb_pengganti' id='".$prefix."imb_pengganti' onclick=\"imb17_replacement($(this).prop('checked'));\" value='1' ".$checked.">&nbsp;IMB Pengganti Hilang & Balik Nama</td>
				</tr>
				<tr><td colspan='2'><br /><br /></td></tr>
				<tr>
					<td colspan='2'>
					<table class='table table-striped' width='100%'>
						<thead>
							<tr>
								<td align='center'>Total Retribusi Bangunan (Rp.)</td>
								<td align='center'>Total Retribusi Prasarana (Rp.)</td>
								<td align='center'>Total Penatausahaan (Rp.)</td>
								<td align='center'>Total Nilai Retribusi (Rp.)</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type='text' class='autofill-bg' name='".$prefix."total_retribusi_bangunan' id='".$prefix."total_retribusi_bangunan' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' value='".($act=='edit'?number_format($curr_data['total_retribusi_bangunan'],0,'.',','):'')."' readonly/></td>
								<td><input type='text' class='autofill-bg' name='".$prefix."total_retribusi_prasarana' id='".$prefix."total_retribusi_prasarana' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' value='".($act=='edit'?number_format($curr_data['total_retribusi_prasarana'],0,'.',','):'')."' readonly/></td>
								<td><input type='text' class='thousand_format1' name='".$prefix."total_penatausahaan' id='".$prefix."total_penatausahaan' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' onkeyup=\"mix_panel2_2_function5();\" value='".($act=='edit'?number_format($curr_data['total_penatausahaan'],0,'.',','):'')."' /></td>
								<td><input type='text' class='autofill-bg' name='".$prefix."grand_total_retribusi' id='".$prefix."grand_total_retribusi' style='width:100%;text-align:right;font-weight:bold;font-size:1.2em' value='".($act=='edit'?number_format($curr_data['grand_total_retribusi'],0,'.',','):'')."' readonly/></td>
							</tr>
						</tbody>
					</table>
					<input type='hidden' name='".$prefix."id_perhitungan' value='".($act=='edit'?$curr_data['id_perhitungan']:'')."'/>
					</td>
				</tr>
			</table>

		</div>
	</div>";

	$prefix = 'imb12_';
	echo "<div class='row' id='retribution-valuation-panel2' style='display:".$display1."'>
		<div class='col col-md-12'>
			<section>
				<div class='row'>
					<label class='label col col-3'>Jenis/Tipe Bangunan<font color='red'>*</font></label>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='".$prefix."jenis_bangunan' id='".$prefix."jenis_bangunan' class='form-control' value='".($act=='edit'?$curr_data['jenis_bangunan']:'Rumah Tinggal')."' required/>
						</label>								
					</div>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='".$prefix."tipe_bangunan' id='".$prefix."tipe_bangunan' class='form-control' value='".($act=='edit'?$curr_data['tipe_bangunan']:'P.II')."' required/>
						</label>								
					</div>
				</div>
			</section>

			<br />

			<table class='table table-striped' style='width:100%'>						
				<thead>
					<tr>
					<td align='center'>Bangunan</td>
					<td align='center'>Luas<br />(m<sup>2</sup>)</td>
					<td align='center'>Nil. Satuan<br />(m<sup>2</sup>)</td>
					<td align='center'>Biaya Bangunan</td>
					<td align='center' width='25%'>Koefisien<br />
						KJ.GB.LB.TB
					</td>
					<td align='center'>Nilai Bangunan<br />(Rp.)</td>
					<td></td>
					</tr>
				</thead>
				<tbody id='valuation2_1-tbody'>";

					$valuation2_rows = array(array('id_rincian_nota'=>'','bangunan'=>'','luas'=>'','nilai_satuan'=>'','biaya_bangunan'=>'','kj'=>'','gb'=>'','lb'=>'','tb'=>'','nilai_bangunan'=>''));
					$grand = 0;

					if($act=='edit')
					{
						$valuation2_rows = array();
						$sql = "SELECT * FROM app_rincian_nota_perhitungan_imb1 WHERE(fk_nota='".$curr_data['id_nota']."')";
						$result = $db->Execute($sql);
						if(!$result)
							echo $db->ErrorMsg();

						while($row = $result->FetchRow())
						{
							$valuation2_rows[] = array('id_rincian_nota'=>$row['id_rincian_nota'],'bangunan'=>$row['bangunan'],'luas'=>$row['luas'],'nilai_satuan'=>number_format($row['nilai_satuan']),
													  'biaya_bangunan'=>number_format($row['biaya_bangunan']),
													  'kj'=>number_format($row['kj'],2,'.',','),'gb'=>number_format($row['gb'],2,'.',','),'lb'=>number_format($row['lb'],2,'.',','),
													  'tb'=>number_format($row['tb'],2,'.',','),'nilai_bangunan'=>number_format($row['nilai_bangunan'])
													  );
							$grand += $row['nilai_bangunan'];
						}
					}

					$i = 0;

					foreach($valuation2_rows as $row)
					{
						$i++;
						echo "
						<tr id='row-".$i."'>
							<td>
							<input type='hidden' name='".$prefix."id_rincian_nota2".$i."' id='".$prefix."id_rincian_nota2".$i."' value='".$row['id_rincian_nota']."'/>
							<input type='text' name='".$prefix."bangunan".$i."' id='".$prefix."bangunan".$i."' style='width:100%;' value='".$row['bangunan']."' required/>
							</td>
							<td><input type='text' class='thousand_format2' name='".$prefix."luas".$i."' id='".$prefix."luas".$i."' style='width:100%;text-align:right;' value='".$row['luas']."' onkeyup=\"mix_panel2_1_function1('".$i."');\" required/></td>
							<td><input type='text' class='thousand_format1' name='".$prefix."nilai_satuan".$i."' id='".$prefix."nilai_satuan".$i."' style='width:100%;text-align:right;' value='".$row['nilai_satuan']."' onkeyup=\"mix_panel2_1_function1('".$i."');\" required/></td>
							<td><input type='text' name='".$prefix."biaya_bangunan".$i."' id='".$prefix."biaya_bangunan".$i."' style='width:100%;text-align:right;' class='autofill-bg' value='".$row['biaya_bangunan']."' readonly/></td>
							<td align='right'>
								<input type='text' class='decimal' name='".$prefix."kj".$i."' id='".$prefix."kj".$i."' size='".$i."' style='text-align:right;' value='".$row['kj']."' onkeyup=\"mix_panel2_1_function2('".$i."')\"/>
								<input type='text' class='decimal' name='".$prefix."gb".$i."' id='".$prefix."gb".$i."' size='".$i."' style='text-align:right;' value='".$row['gb']."' onkeyup=\"mix_panel2_1_function2('".$i."')\"/>
								<input type='text' class='decimal' name='".$prefix."lb".$i."' id='".$prefix."lb".$i."' size='".$i."' style='text-align:right;' value='".$row['lb']."' onkeyup=\"mix_panel2_1_function2('".$i."')\"/>
								<input type='text' class='decimal' name='".$prefix."tb".$i."' id='".$prefix."tb".$i."' size='".$i."' style='text-align:right;' value='".$row['tb']."' onkeyup=\"mix_panel2_1_function2('".$i."')\"/>
							</td>
							<td><input type='text' name='".$prefix."nilai_bangunan".$i."' id='".$prefix."nilai_bangunan".$i."' style='width:100%;text-align:right;' class='autofill-bg' value='".$row['nilai_bangunan']."' readonly/></td>
							<td>";
							if($i>1)
							{
								echo "<button type='button' id='panel2_1_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_row_panel2_1('".$i."');\"><i class='fa fa-trash-o'></i></button>";
							}
							echo "</td>
						</tr>";
					}

				echo "</tbody>
				<tfoot>
					<tr>
						<td colspan='4'><a href='javascript:;' onclick=\"add_valuation_table_row2_1();\"><i class='fa fa-plus'></i> Tambah Baris</a></td>
						<td align='right'><b>TOTAL NILAI BANGUNAN</b></td>
						<td>
							<input type='text' name='".$prefix."total_perhitungan_nb' id='".$prefix."total_perhitungan_nb' value='".number_format($grand)."' class='autofill-bg' style='width:100%;text-align:right;' readonly/>
						</td>
					</tr>
				</tfoot>
			</table>
			<input type='hidden' id='".$prefix."n_valuation_row2_1' name='".$prefix."n_valuation_row2_1' value='".$i."'/>
			<br />
			<table class='table table-striped' style='width:100%'>
				<thead>
					<tr>
						<td align='center' colspan='2'>Prosentase Biaya (%)</td>
						<td align='center'>Biaya IMB (Rp.)</td>								
					</tr>
				</thead>
				<tbody>
					<tr><td>Koef. Permohonan</td>
					<td width='10%'><input type='text' class='decimal' name='".$prefix."koef_permohonan' id='".$prefix."koef_permohonan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_permohonan'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('permohonan')\"/></td>
					<td width='25%'><input type='text' name='".$prefix."nilai_permohonan' id='".$prefix."nilai_permohonan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_permohonan']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Penatausahaan</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_penatausahaan' id='".$prefix."koef_penatausahaan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_penatausahaan'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('penatausahaan')\"/></td>
					<td><input type='text' name='".$prefix."nilai_penatausahaan' id='".$prefix."nilai_penatausahaan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_penatausahaan']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Plat Nomor</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_plat_nomor' id='".$prefix."koef_plat_nomor' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_plat_nomor'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('plat_nomor')\"/></td>
					<td><input type='text' name='".$prefix."nilai_plat_nomor' id='".$prefix."nilai_plat_nomor' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_plat_nomor']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Penerbitan Srtif IMB</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_penerbitan_srtif_imb' id='".$prefix."koef_penerbitan_srtif_imb' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_penerbitan_srtif_imb'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('penerbitan_srtif_imb')\"/></td>
					<td><input type='text' name='".$prefix."nilai_penerbitan_srtif_imb' id='".$prefix."nilai_penerbitan_srtif_imb' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_penerbitan_srtif_imb']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Verifikasi Data Tkns</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_verifikasi_data_tkns' id='".$prefix."koef_verifikasi_data_tkns' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_verifikasi_data_tkns'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('verifikasi_data_tkns')\"/></td>
					<td><input type='text' name='".$prefix."nilai_verifikasi_data_tkns' id='".$prefix."nilai_verifikasi_data_tkns' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_verifikasi_data_tkns']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pengukuran</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_pengukuran' id='".$prefix."koef_pengukuran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pengukuran'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('pengukuran')\"/></td>
					<td><input type='text' name='".$prefix."nilai_pengukuran' id='".$prefix."nilai_pengukuran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pengukuran']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pematokan GSJ/GSS</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_pematokan_gsj_gss' id='".$prefix."koef_pematokan_gsj_gss' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pematokan_gsj_gss'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('pematokan_gsj_gss')\"/></td>
					<td><input type='text' name='".$prefix."nilai_pematokan_gsj_gss' id='".$prefix."nilai_pematokan_gsj_gss' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pematokan_gsj_gss']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pem Gbr Rencana</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_gbr_rencana' id='".$prefix."koef_gbr_rencana' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_gbr_rencana'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('gbr_rencana')\"/></td>
					<td><input type='text' name='".$prefix."nilai_gbr_rencana' id='".$prefix."nilai_gbr_rencana' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_gbr_rencana']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pengawasan Lain</td>
					<td><input type='text' class='decimal' name='".$prefix."koef_pengawasan_izin' id='".$prefix."koef_pengawasan_izin' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pengawasan_izin'],2,'.',','):'')."' onkeyup=\"mix_panel2_1_function3('pengawasan_izin')\"/></td>
					<td><input type='text' name='".$prefix."nilai_pengawasan_izin' id='".$prefix."nilai_pengawasan_izin' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pengawasan_izin']):'')."' class='autofill-bg' readonly/></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2' align='right'><b>TOTAL IMB</b></td>
						<td>
							<input type='hidden' name='".$prefix."id_rincian_nota2' value='".($act=='edit'?$curr_data['id_rincian_nota']:'')."'/>
							<input type='text' name='".$prefix."total_nilai_imb' id='".$prefix."total_nilai_imb' class='autofill-bg' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['total_nilai_imb']):'')."' readonly/>
						</td>
					</tr>
					<tr>
						<td colspan='3' align='right'>";
							$checked = ($act=='edit'?($curr_data['imb_pengganti']=='1'?'checked':''):'');
							echo "<input type='checkbox' name='".$prefix."imb_pengganti' id='".$prefix."imb_pengganti' value='".$diskon_imb_pengganti."' onclick=\"count_total_imb12()\" ".$checked."/> IMB Pengganti (Diskon ".$diskon_imb_pengganti."%)
						</td>
					</tr>
					<tr>
						<td colspan='2' align='right'><b>TOTAL IMB SETELAH DISKON</b></td>
						<td>							
							<input type='text' name='".$prefix."grand_total_imb' id='".$prefix."grand_total_imb' class='autofill-bg' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['grand_total_imb']):'')."' readonly/>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>";
?>