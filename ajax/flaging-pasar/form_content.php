<?php
	require_once("inc/init.php");
	require_once("list_sql2.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");



	
	$global = new global_obj($db);
	$DML1 = new DML('payment_retribusi_pasar.',$db);
	$DML2 = new DML('app_skrd_pasar',$db);

$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

    $id_value = ($act=='edit'?$_GET['id']:''); 	
	
	
/*	
$arr_field = array('id_pembayaran');

$last_id =  $DML1->getCurrentData($act,$arr_field,$id_value);
*/
$form_id = 'form';
	
	$jenis_retribusi = '';	
	if($act=='edit')
	{
		$jenis_retribusi = $global->get_retribution_ref($curr_data['kd_rekening'],'jenis_retribusi');
	}

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
	
		
$maxid = $maxidx->FetchRow();
$last_id=$maxid['last'];

		
$maxidSKRD = $maxidSKRDx->FetchRow();
$last_idSKRD=$maxidSKRD['last'];

$maxidSKRDxs = $maxidSKRDzx->FetchRow();
$last_idSKRDz=$maxidSKRDxs['last'];



?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Flaging</h4>
</div>

<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>"  method="POST" class="smart-form">
		
	
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
    	<input type="hidden" name="tipe_retribusi" value="1"/>
		<fieldset>
			
<div class="row">
				<div class="col col-6">
				
				<section>
						<div class="row">
						<label class="label col col-4">Noskrd</label>
						<div class="col col-4">
						<label class="input">
																
						<input type="hidden" name="id_pembayaran" value="<?=$last_id+1;?>"/>
						<input type="hidden" name="id_skrd" value="<?=$last_idSKRDz+1;?>"/>
						<input type="text" name="no_skrd" class="form-control disabled-bg" id="no_registrasi" onKeypress = "return numbersonly(this, event)" value="<?=$last_idSKRD+1;?>" />
								
						</label><!--<a onClick="window.location.reload()">Refresh</a>-->
						</div>
							
						</div>
				
					</section>
					<section>
						<div class="row">
							<label class="label col col-4">Jenis Retribusi</label>
							<div class="col col-8">
								
								<label class="state">
									<select name="kd_rekening" class="form-control" id="kd_rekening" >
										<option value="4120120" selected>4120120</option>
										
									</select>
									<select name="nm_rekening" class="form-control" id="nm_rekening" >
										<option value="Retribusi Pelayanan Pasar" selected>Retribusi Pelayanan Pasar</option>
										
									</select>
								
								</label>

								
							</div>

						</div>
					</section>
										
											
<!--
					
					<section>
				<div class="row">
									
									<label class="control-label col-md-4" for="npwrd">NPWRD <font color="red">*</font></label>
									<div class="col-md-3">
						                <div class="input-group input-group-md">						                    
						                    <div class="icon-addon addon-md">
						                        <input type="text" name="npwrd" id="npwrd" class="form-control" readonly required/>
						                        <label for="npwrd" class="glyphicon glyphicon-search" rel="tooltip" title="NPWRD"></label>
						                    </div>						                    

						                    <span class="input-group-btn">
						                        <a href="ajax/<?=$fn;?>/wr_list.php?fn=<?=$fn;?>" class="btn btn-default" data-toggle="modal" data-target="#browseModal">...</a>
						                    </span>
						                </div>
									</div>
									
									<!-- MODAL PLACE HOLDER 
									<div class="modal fade" id="browseModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content"></div> 
										</div>
									</div>
									<!-- END MODAL 
								</div>
								
							
					
					</section>-->
					
							
						
				
					
					<section>
					<div class="row">
							<label class="label col col-4">NPWRD<font color="red">*</font></label>
							<div class="col col-8">
								
								<label class='input' id='npwrd'>
									<input type='text' name='npwrd' id='npwrd' class='form-control'  style='display:".$display1."' ".$ext_attr1."/>
								</label>

							
								</label>
							

							</div>
						</div>
						</section>
						<section>
						<div class="row">
							<label class="label col col-4">Nama WR<font color="red">*</font></label>
							<div class="col col-8">
								
								<label class='input' id='wp_wr_nama'>
									<input type='text' name='wp_wr_nama' id='wp_wr_nama' class='form-control'  style='display:".$display1."' ".$ext_attr1."/>
								</label>

							
								</label>
							

							</div>
						</div>
						</section>
						<section>
						<div class="row">
							<label class="label col col-4">Nama WR Alamat<font color="red">*</font></label>
							<div class="col col-8">
								
								<label class='input' id=''>
									<input type='text' name='wp_wr_alamat' id='' class='form-control'  style='display:".$display1."' ".$ext_attr1."/>
								</label>

							
								</label>
							

							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Kota/Kabupaten<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kota" id="kota" class="form-control" value="BEKASI" required/>
								</label>
							</div>
						</div>
					</section>
		
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
						<!--<div class="row">
							<label class="label col col-4">Kode Billing</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kd_billing" value="<?//=$npwrd;?><?//=$last_idSKRDz+1;?>" class="form-control"  maxlength="35"/>
									
								</label>
							</div>
						</div>-->
						
					</section>
		<input type="hidden" name="ntpd" id="" class="form-control"  maxlength="25"/>
						<input type="hidden" name="pembayaran_ke" value="1" class="form-control"  value="<?=$last_ntpd;?>" maxlength="45"/>
					<section>
						<div class="row">
							<label class="label col col-4">Total Retribusi<font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="total_retribusi" id="" class="form-control"  required/>
								</label>
							</div>
						</div>
						<input type="hidden" name="denda" value="0" class="form-control"  required/>
					</section>
					<section>
						<div class="row">
							<label class="label col col-4">Total Bayar<font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="total_bayar" id="" class="form-control"  required/>
								</label>
							</div>
						</div>
					</section>


									

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. penetapan<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="tgl_penetapan" id="tgl_form_diterima" class="datepicker" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_form_diterima'],'shortDate'):'')?>" data-dateformat="dd-mm-yy" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Bayar<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="tgl_bayar" id="tgl_batas_kirim" class="datepicker" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_batas_kirim'],'shortDate'):'')?>" data-dateformat="dd-mm-yy" required/>
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



 <!-- Script -->
    <script type='text/javascript' >
    $( function() {
  
        $( "#npwrdx" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "fetchData.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#npwrdx').val(ui.item.label); // display the selected text
                $('#wp_wr_namax').val(ui.item.value); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $( "#npwrdx" ).val( ui.item.label );
                $( "#wp_wr_namax" ).val( ui.item.value );
                return false;
            },
        });
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

    </script>
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
