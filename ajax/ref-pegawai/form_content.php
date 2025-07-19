<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML = new DML('app_ref_pegawai',$db);
	$DML2 = new DML('app_ref_instansi',$db);	

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

    $id_name = 'id_pegawai';
    $id_value = ($act=='edit'?$_GET['id']:$global->get_incrementID('app_ref_pegawai','id_pegawai'));

    $arr_field = array('nama','nip','pangkat','jabatan','kd_instansi','eksternal');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'employee-form';	
	
	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Pegawai</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">Nama<font color="red">*</font></label>
							<div class="col col-9">
								<label class="input">
									<input type="text" name="nama" id="nama" class="form-control" value="<?=$curr_data['nama']?>"/>
								</label>

								<!-- div class="note">
									<a href="javascript:void(0)">note here</a>
								</div -->
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">NIP</label>
							<div class="col col-6">
								<label class="input">
									<input type="text" name="nip" id="nip" class="form-control" value="<?=$curr_data['nip']?>"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Pangkat</label>
							<div class="col col-6">
								<label class="input">
									<input type="text" name="pangkat" id="pangkat" class="form-control" value="<?=$curr_data['pangkat']?>"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Jabatan</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="jabatan" id="jabatan" class="form-control" value="<?=$curr_data['jabatan']?>"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-8">
								<label class="checkbox">
									<input type="checkbox" name="eksternal" id="eksternal" onclick="control_org_element($(this).is(':checked'))" value="2" <?php echo ($act=='edit'?($curr_data['eksternal']=='1'?'checked':''):''); ?>/>
									<i></i>Luar <?=$_ORGANIZATION_ACR;?>
								</label>
							</div>
						</div>
					</section>
					<?php
						$display = ($curr_data['eksternal']=='1'?'block':'none');
						$ext_attr = ($curr_data['eksternal']=='1'?'required':'disabled');
					?>
					<section id="instansi_pegawai" style="display:<?=$display;?>">
						<div class="row">
							<label class="label col col-3">Instansi<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">									
									<select name="kd_instansi" id="kd_instansi" class="form-control" <?=$ext_attr;?>>
										<option value=""></option>
										<?php
											$opts = $DML2->fetchAllData();
											foreach($opts as $opt)
											{
												$selected = ($opt['kd_instansi']==$curr_data['kd_instansi']?'selected':'');
												echo "<option value='".$opt['kd_instansi']."' ".$selected.">".$opt['nm_instansi']."</option>";
											}
										?>
									</select>
								</label>
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
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	    function control_org_element(status){
	    	$instansi_pegawai = $('#instansi_pegawai');
	    	$kd_instansi = $('#kd_instansi');

	    	$instansi_pegawai.css('display',(status?'block':'none'));
	    	$kd_instansi.attr('required',status);
	    	$kd_instansi.attr('disabled',!status);
	    }

	</script>

</div>
