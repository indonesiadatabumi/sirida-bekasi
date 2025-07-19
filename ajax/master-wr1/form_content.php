<?php
	

	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global = new global_obj($db);
	$DML1 = new DML('app_reg_wr',$db);
	$DML2 = new DML('app_ref_instansi',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	
    $id_name = 'npwrd';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('no_registrasi','nm_wp_wr','alamat_wp_wr','no_tlp','kelurahan','kecamatan','kota','kd_pos',
    					'tgl_form_diterima','tgl_batas_kirim','tgl_pendaftaran','npwrd','tipe_wr','kd_rekening');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'wr-reg-form';
	
	$no_registrasi = ($act=='add'?$global->get_no_registerNum():$curr_data['no_registrasi']);

	
	$npwrd = ($act=='add'?$global->get_no_npwrd('1'):$curr_data['npwrd']);
	//$npwrd = ($act=='add'?$global->get_npwrd('1'):$curr_data['npwrd']);
	
	
	$jenis_retribusi = '';	
	if($act=='edit')
	{
		$jenis_retribusi = $global->get_retribution_ref($curr_data['kd_rekening'],'jenis_retribusi');
	}

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Registrasi WR SKRD</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
    	<input type="hidden" name="tipe_retribusi" value="1"/>
		<fieldset>
			
<div class="row">
				<div class="col col-6">
				
				<section>
						<div class="row">
							<label class="label col col-4">No. Registrasi</label>
							<div class="col col-4">
								<label class="input">
																
								<input type="text" name="no_registrasi" class="form-control disabled-bg" id="no_registrasi" onKeypress = "return numbersonly(this, event)" value="<?=$no_registrasi;?>" readonly/>
								
								</label><!--<a onClick="window.location.reload()">Refresh</a>-->
							</div>
							
						</div>
				
					</section>
					<section>
						<div class="row">
							<label class="label col col-4">Jenis Retribusi</label>
							<div class="col col-8">
								
								<label class="state">
									<select name="kd_rekening" class="form-control" id="kd_rekening" onchange="changeValue(this.value)" title="<?=$jenis_retribusi;?>" <?php echo ($act=='edit'?'disabled':'') ?>>
										<option value="" selected></option>
										<?php

											$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' 
													and kd_rekening in (select substring(kd_rekening from 1 for 5) from app_ref_jenis_retribusi where non_karcis='1') 
													and denda='0'
													ORDER BY id_jenis_retribusi ASC";
											
											$result1 = $db->Execute($sql);
											$jsArray = "var idi = new Array();\n"; 

											
											
											while($row1 = $result1->FetchRow())
											{
												echo "<optgroup label='".$row1['jenis_retribusi']."'>";
												
												$sql = "SELECT * FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '".$row1['kd_rekening']."%' 
														AND length(kd_rekening)>5 AND non_karcis='1' ORDER BY id_jenis_retribusi ASC";
												$result2 = $db->Execute($sql);
												
												while($row2 = $result2->FetchRow())
												{
													$selected = ($act=='edit'?($row2['kd_rekening']==$curr_data['kd_rekening']?'selected':''):'');
													echo "<option value='".$row2['kd_rekening']."' ".$selected.">".$row2['jenis_retribusi']."</option>";
													
													$jsArray .= "idi['" . $row2['kd_rekening'] . "'] = {id_jenis_retribusi:'" . addslashes($row2['id_jenis_retribusi']) . "'};\n"; 
												
												}
												
												echo "</optgroup>";
												
											}
											
											
										?>
									</select>
									<?php
								
										if($act=='edit')
										{
											echo "<input type='hidden' name='kd_rekening' value='".$curr_data['kd_rekening']."'>";
										}
									?>
								</label>

								<div class="note">
									jika dikosongkan WR dapat melakukan semua transaksi
								</div>
							</div>

						</div>
					</section>
					
							
						
					
					

<script type="text/javascript">
    <?php echo $jsArray; ?>
    function changeValue(kd_rekening) {
      document.getElementById("id_jenis_retribusi").value = idi[kd_rekening].id_jenis_retribusi;
    };
</script>
				
					<section>
						<div class="row">
							<label class="label col col-4">&nbsp;</label>
							<div class="col col-8">
								<div class="inline-group">
									<?php
									echo "
									<label class='radio'>
										<input type='radio' name='tipe_wr' id='tipe_wr1' value='1' onclick=\"control_wr_data('1')\" ".($act=='edit'?($curr_data['tipe_wr']=='1'?'checked':''):'checked').">
										<i></i>Perorangan
									</label>
									<label class='radio'>
										<input type='radio' name='tipe_wr' id='tipe_wr2' value='2' onclick=\"control_wr_data('2')\" ".($act=='edit'?($curr_data['tipe_wr']=='2'?'checked':''):'')."/>
										<i></i>Instansi/SKPD
									</label>";
									?>
								</div>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-4">Nama WR<font color="red">*</font></label>
							<div class="col col-8">
								<?php
								$display1 = ($act=='edit'?($curr_data['tipe_wr']=='1'?'block':'none'):'block');
								$display2 = ($act=='edit'?($curr_data['tipe_wr']=='2'?'block':'none'):'none');
								$ext_attr1 = ($act=='edit'?($curr_data['tipe_wr']=='1'?'required':'disabled'):'required');
								$ext_attr2 = ($act=='edit'?($curr_data['tipe_wr']=='2'?'required':'disabled'):'disabled');
								echo "
								<label class='input' id='nm_wp1'>
									<input type='text' name='nm_wp_wr' id='nm_wp_wr1' class='form-control' value='".$curr_data['nm_wp_wr']."' style='display:".$display1."' ".$ext_attr1."/>
								</label>

								<label class='state' id='nm_wp2'>
									<select name='nm_wp_wr' id='nm_wp_wr2' class='form-control' onchange=\"get_organitation_data(this.value);\" style='display:".$display2."' ".$ext_attr2.">
										<option value='' selected></option>";
										
											$opts = $DML2->fetchAllData();
											foreach($opts as $row)
											{
												$selected = ($act=='edit'?($row['nm_instansi']==$curr_data['nm_wp_wr']?'selected':''):'');
												echo "<option value='".$row['kd_instansi']."_".$row['nm_instansi']."' ".$selected.">".$row['nm_instansi']."</option>";
											}
										
									echo "</select>
								</label>";
								?>

							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Kota/Kabupaten<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kota" id="kota" class="form-control" value="<?=$curr_data['kota'];?>" required/>
								</label>
							</div>
						</div>
					</section>
				<!--
					<section>
					
						<div class="row">
							<label class="label col col-4">Kecamatan</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kecamatan" id="kecamatan" class="form-control" value="<?//=$curr_data['kecamatan'];?>" />
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Kelurahan</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kelurahan" id="kelurahan" class="form-control" value="<?//=$curr_data['kelurahan'];?>" />
								</label>
							</div>
						</div>
					</section>
					-->
						<section>
					<div class="row">
					
							<label class="label col col-4">Kecamatan<font color="red">*</font></label>
							<div class="col col-8">
								
								<label class="state">
									<select name="kecamatan" class="form-control" id="kecamatan" onchange="changeValue(this.value)" title="<?=$kecamatan;?>" <?php echo ($act=='edit'?'disabled':'')?> required>
										<option value="" selected></option>
										<?php
									
											$sql = "SELECT * FROM kecamatan 
													ORDER BY camat_id ASC";
											
											$result1 = $db->Execute($sql);
										//	$jsArray4 = "var idi = new Array();\n"; 

											
											
											while($row4 = $result1->FetchRow())
											{
												
												
												//	$selected = ($act=='edit'?($row4['camat_id']==$curr_data['camat_id']?'selected':''):'');
													echo "<option value='".$row4['camat_nama']."' ".$selected.">".$row4['camat_nama']."</option>";
													
												//	$jsArray4 .= "idi['" . $row4['camat_id'] . "'] = {camat_id:'" . addslashes($row4['camat_id']) . "'};\n"; 
												
												
											}
										
										
										?>
									</select>
									<?php
								
										if($act=='edit')
										{
											echo "<input type='hidden' name='kecamatan' value='".$curr_data['camat_nama']."'>";
										}
									?>
								</label>

								
							</div>

						</div>
					
					</section>
		
					<section>
				
						<div class="row">
							<label class="label col col-4">Kelurahan<font color="red">*</font></label>
							<div class="col col-8">
								
								<label class="state">
									<select name="kelurahan" class="form-control" id="kelurahan" onchange="changeValue(this.value)" title="<?=$kelurahan;?>" <?php echo ($act=='edit'?'disabled':'') ?> required>
										<option value="" selected></option>
										<?php
									
											$sql = "SELECT * FROM kelurahan
													ORDER BY lurah_id ASC";
											
											$result1 = $db->Execute($sql);
											$jsArray3 = "var idi = new Array();\n"; 

											
											
											while($row1 = $result1->FetchRow())
											{
												
												
													$selected = ($act=='edit'?($row1['lurah_id']==$curr_data['lurah_id']?'selected':''):'');
													echo "<option value='".$row1['lurah_nama']."' ".$selected.">".$row1['lurah_nama']."</option>";
													
													$jsArray3 .= "idi['" . $row1['lurah_id'] . "'] = {lurah_id:'" . addslashes($row1['lurah_id']) . "'};\n"; 
												
												
											}
											
											
										?>
									</select>
									<?php
								
										if($act=='edit')
										{
											echo "<input type='hidden' name='kelurahan' value='".$curr_data['lurah_nama']."'>";
										}
									?>
								</label>

								
							</div>

						</div>
					</section>
						
			
				
				</div>
				<div class="col col-6">
					<section>
						<div class="row">
							<label class="label col col-4">Kode Pos</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kd_pos" id="kd_pos" class="form-control" value="<?=$curr_data['kd_pos']?>" maxlength="5"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Alamat WR<font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="alamat_wp_wr" id="alamat_wp_wr" class="form-control" value="<?=$curr_data['alamat_wp_wr']?>" required/>
								</label>
							</div>
						</div>
					</section>


					<section>
						<div class="row">
							<label class="label col col-4">No. Telepon</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="no_tlp" id="no_tlp" class="form-control" value="<?=$curr_data['no_tlp']?>" maxlength="15" />
								</label>
							</div>
						</div>
					</section>					

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Form Diterima<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="tgl_form_diterima" id="tgl_form_diterima" class="datepicker" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_form_diterima'],'shortDate'):'')?>" data-dateformat="dd-mm-yy" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Batas Kirim<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="tgl_batas_kirim" id="tgl_batas_kirim" class="datepicker" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_batas_kirim'],'shortDate'):'')?>" data-dateformat="dd-mm-yy" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Pendaftaran<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="tgl_pendaftaran" id="tgl_pendaftaran" class="datepicker" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_pendaftaran'],'shortDate'):'')?>" data-dateformat="dd-mm-yy" required/>
								</label>
							</div>
						</div>
					</section>

					<header>
						<b><font color="orange">Nomor Pokok Wajib Retribusi Daerah (NPWRD)</font></b>
					</header>
					
					<section style="margin-top:10px;">
						<div class="row"><label class="label col col-4"><b>R.</b></label>
						<div class="col col-4">
						<div class="inline-group">
								<div class="input">
						<input type="text" name="id_jenis_retribusi" class="form-control disabled-bg" width="25px" id="id_jenis_retribusi" readonly/>
							
	<input type="text" name="npwrd" id="npwrd" value="<?php echo $npwrd;?>" style="text-align:center;font-weight:bold;background:#ed7efc;color:white;font-size:1.2em" readonly/>
							</div>
							</div>
							</div>
						</div>
					</section>

				</div>
		</fieldset>

		<footer>
			<button type="submit" class="btn btn-primary">
				Simpan
			</button>
			<button type="button" class="btn btn-default" id="close-modal-form" data-dismiss="modal">
				Batal
			</button>
		</footer>

	</form>

	<script>
		// Load form valisation dependency
		// loadScript("js/plugin/jquery-form/jquery-form.min.js", $loginForm);
		
		var form_id = '<?php echo $form_id;?>';
	    var $input_form = $('#'+form_id);

		$(document).ready(function(){
			$(".datepicker").datepicker(
				{ 
					dateFormat: 'dd-mm-yy',
					prevText: '<i class="fa fa-chevron-left"></i>',
			    	nextText: '<i class="fa fa-chevron-right"></i>',
				});
			$("#tgl_lahir").mask('99-99-9999');
			$("#tgl_kartu_keluarga").mask('99-99-9999');
			$("#tgl_form_diterima").mask('99-99-9999');
			$("#tgl_batas_kirim").mask('99-99-9999');
			$("#tgl_pendaftaran").mask('99-99-9999');

			var stat = $input_form.validate({
				// Rules for form validation			

				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
					}
			});

			var act_lbl = '<?php echo $act_lbl;?>';

		    $input_form.submit(function(){
		        if(stat.checkForm())
		        {
		            ajax_manipulate.reset_object();
		            ajax_manipulate.set_plugin_datatable(true)
		                           .set_content('#list-of-data')
	                           	   .set_loading('#preloadAnimation')                           	   
	                               .set_form($input_form)
	                               .enable_pnotify()
	                               .submit_ajax(act_lbl);
	            	$('#close-modal-form').click();

		            return false;
		        }
		    });
		});


	</script>

</div>
