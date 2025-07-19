<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML = new DML('app_ref_jenis_retribusi',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

    $id_name = 'id_jenis_retribusi';
    $id_value = ($act=='edit'?$_GET['id']:$global->get_incrementID('app_ref_jenis_retribusi','id_jenis_retribusi'));

    $arr_field = array('kd_rekening','jenis_retribusi','dasar_hukum_pengenaan','item','non_karcis','karcis');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'retribution-type-form';	
	
	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Jenis Retribusi</h4>
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
							<label class="label col col-3">ID Jenis Retribusi</label>
							<div class="col col-3">
								<label class="input">
									<input type="text" id="<?=$id_name?>" name="<?=$id_name?>" value="<?=$id_value?>" class="form-control" maxlength=10 onkeypress="return only_number(event,this)" required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Kode Rekening <font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="kd_rekening" id="kd_rekening" class="form-control" value="<?=$curr_data['kd_rekening']?>" required/>
								</label>

								<!-- div class="note">
									<a href="javascript:void(0)">note here</a>
								</div -->
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Jenis Retribusi <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="jenis_retribusi" id="jenis_retribusi" class="form-control" value="<?=$curr_data['jenis_retribusi']?>" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Dasar Pengenaan</label>
							<div class="col col-9">
								<table width="100%" class="" cellspacing="10">
									<tbody id="retribution-regulation-tbody">
									<?php
									$dasar_pengenaan_rows = array('');
									
									if($act=='edit')
									{
										$dasar_pengenaan_rows = explode('|%|',$curr_data['dasar_hukum_pengenaan']);
									}

									$i = 0;

									foreach($dasar_pengenaan_rows as $row)
									{
										$i++;
										echo "
										<tr id='row-".$i."'>
											<td style='padding:5px 0 5px 0;'>
												<label class='input'>
													<input type='text' name='dasar_hukum_pengenaan".$i."' id='dasar_hukum_pengenaan".$i."' class='form-control' value='".$row."'/>
												</label>
											</td>
											<td width='5%'>";
											if($i>1){
												echo "<button type='button' id='regulation_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_regulation_row('".$i."');\"><i class='fa fa-trash-o'></i></button>";
											}
											echo "</td>
										</tr>";
									}
									?>
									</tbody>
									<tfoot>
										<tr>
										<td>
											<input type="hidden" id="n_regulation_row" name='n_regulation_row' value="<?=$i;?>"/>
											<a href="javascript:;" onclick="add_regulation_row();"><i class='fa fa-plus'></i> Tambah Baris</a>
										</td>
										</tr>
									</tfoot>
								</table>								
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-8">
								<label class="checkbox">
									<input type="checkbox" name="item" id="item" value="1" <?php echo ($act=='edit'?($curr_data['item']=='1'?'checked':''):'checked'); ?>/>
									<i></i>Item Rekening
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-8">
								<label class="checkbox">
									<input type="checkbox" name="karcis" id="karcis" value="2" <?php echo ($act=='edit'?($curr_data['karcis']=='1'?'checked':''):''); ?>/>
									<i></i>Karcis
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-8">
								<label class="checkbox">
									<input type="checkbox" name="non_karcis" id="non_karcis" value="2" <?php echo ($act=='edit'?($curr_data['non_karcis']=='1'?'checked':''):''); ?>/>
									<i></i>Non Karcis
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

	    function add_regulation_row()
	    {
	    	var $tbody = $('#retribution-regulation-tbody'), $lc_tbody = $('#retribution-regulation-tbody tr:last-child'), $n_regulation_row = $('#n_regulation_row');
	    	var last_row_id = $lc_tbody.attr('id');
	    	
	    	x = last_row_id.split('-');
	    	last_order = x[1];
	    	new_order = parseInt(last_order)+1;
											
	    	new_row = "<tr id='row-"+new_order+"'>"+
	    			  "<td style='padding:5px 0 5px 0;'><label class='input'><input type='text' name='dasar_hukum_pengenaan"+new_order+"' id='dasar_hukum_pengenaan"+new_order+"' class='form-control' required/></label></td>"+
					  "<td width='5%'><button type='button' id='regulation_delete_row"+new_order+"' class='btn btn-default btn-xs' onclick=\"delete_regulation_row('"+new_order+"');\"><i class='fa fa-trash-o'></i></button></td>"+
	    			  "</tr>";

	    	$n_regulation_row.val(new_order);
	    	$tbody.append(new_row);
	    }

	    function delete_regulation_row(order_num)
	    {
	    	var $tr = $('#retribution-regulation-tbody > tr');
	    	$tr.remove('#row-'+order_num);	    	
	    }
	</script>

</div>
