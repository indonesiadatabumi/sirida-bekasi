<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML1 = new DML('app_ba_stbb',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	$cond_type = $_GET['cond_type'];
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$curr_year = date('Y');

    $id_name = 'id_berita_acara';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('nm_pihak_kesatu','nip_pihak_kesatu','jbt_pihak_kesatu',
    				   'nm_pihak_kedua','nip_pihak_kedua','jbt_pihak_kedua',
    				   'tgl_pengambilan','npwrd','fk_skrd','nm_pemohon','nip_pemohon','jabatan_pemohon',
    				   'tgl_berita_acara','no_berita_acara','tgl_surat_permohonan','no_surat_permohonan');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'berita-acara-form';
    

    $nba = ($act=='add'?$global->get_new_ba_number():$curr_data['no_berita_acara']);

    $sql = "SELECT a.id_permohonan,a.nm_rekening,b.no_skrd FROM app_permohonan_karcis as a 
    		INNER JOIN (SELECT no_skrd,id_skrd FROM app_skrd WHERE thn_retribusi='".$curr_year."') as b ON (a.fk_skrd=b.id_skrd)
			WHERE id_permohonan NOT IN (SELECT fk_permohonan FROM app_dtl_ba_stbb ".($act=='edit'?"WHERE fk_berita_acara<>'".$id_value."'":"").")
			ORDER BY b.no_skrd ASC";

	$result = $db->Execute($sql);
	$arr_perforasi = array();
	while($row = $result->FetchRow()){
		$arr_perforasi[] = array('id_permohonan'=>$row['id_permohonan'],'no_skrd'=>sprintf('%04s',$row['no_skrd']),'nm_rekening'=>$row['nm_rekening']);
	}

	$perforasi_opts = "<option value=''></option>";
	foreach($arr_perforasi as $opt){
		$perforasi_opts .= "<option value='".$opt['id_permohonan']."'>".$opt['no_skrd']." - ".$opt['nm_rekening']."</option>";
	}

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker(
				{ 
					dateFormat: 'dd-mm-yy',
					prevText: '<i class="fa fa-chevron-left"></i>',
			    	nextText: '<i class="fa fa-chevron-right"></i>',
				});
		$("#tgl_skrd").mask('99-99-9999');
		$("#tgl_pengambilan").mask('99-99-9999');
		$("#tgl_surat_permohonan").mask('99-99-9999');
	});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form BA Serah Terima Benda Berharga</h4>
</div>

<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>		
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
    	<input type="hidden" name="cond_type" value="<?=$cond_type?>"/>
    	<input type="hidden" name="tgl_awal" value="<?=$tgl_awal?>"/>
    	<input type="hidden" name="tgl_akhir" value="<?=$tgl_akhir?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">					

					<section>
						<div class="row">
							<label class="label col col-4">No. Berita Acara <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input state-disabled">
									<input type="text" name="no_berita_acara" class="form-control" id="no_berita_acara" value="<?=$nba;?>" required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Nama Pihak Kesatu <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="nm_pihak_kesatu" id="nm_pihak_kesatu" value="<?=$curr_data['nm_pihak_kesatu'];?>" class="form-control" required/>
								</label>								
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">NIP Pihak Kesatu <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="nip_pihak_kesatu" class="form-control" id="nip_pihak_kesatu" value="<?=$curr_data['nip_pihak_kesatu']?>" onkeypress="return only_number(event,this);" maxlength=18 required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Jabatan Pihak Kesatu <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="jbt_pihak_kesatu" class="form-control" id="jbt_pihak_kesatu" value="<?=$curr_data['jbt_pihak_kesatu'];?>" required/>
								</label>
							</div>
						</div>
					</section>
					<section>
						<div class="row">
							<label class="label col col-4">Nama Pihak Kedua <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="nm_pihak_kedua" id="nm_pihak_kedua" value="<?=$curr_data['nm_pihak_kedua'];?>" class="form-control" required/>
								</label>								
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">NIP Pihak Kedua <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="nip_pihak_kedua" class="form-control" id="nip_pihak_kedua" value="<?=$curr_data['nip_pihak_kedua']?>" onkeypress="return only_number(event,this);" maxlength=18 required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Jabatan Pihak Kedua <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="jbt_pihak_kedua" class="form-control" id="jbt_pihak_kedua" value="<?=$curr_data['jbt_pihak_kedua'];?>" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Berita Acara <font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="tgl_berita_acara" id="tgl_berita_acara" value="<?=indo_date_format(($act=='edit'?$curr_data['tgl_berita_acara']:$_CURR_DATE),'shortDate')?>" class="form-control datepicker" required/>
								</label>
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">No. Surat Permohonan <font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="no_surat_permohonan" class="form-control" id="no_surat_permohonan" value="<?=$curr_data['no_surat_permohonan'];?>" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Surat Permohonan <font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="tgl_surat_permohonan" id="tgl_surat_permohonan" value="<?=indo_date_format(($act=='edit'?$curr_data['tgl_surat_permohonan']:$_CURR_DATE),'shortDate')?>" class="form-control datepicker" required/>
								</label>
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Permintaan Perforasi</label>
							<div class="col col-8">
								<table border=0 class="table" width="100%" cellspacing=2>
									<tbody id="dtl_ba-tbody">
										<?php

										$dtl_ba_rows = array(array('id_permohonan'=>''));										

										if($act=='edit')
										{
											$dtl_ba_rows = array();
											$sql = "SELECT * FROM app_dtl_ba_stbb WHERE(fk_berita_acara='".$id_value."')";
											$result = $db->Execute($sql);
											if(!$result)
												echo $db->ErrorMsg();

											while($row = $result->FetchRow())
											{
												$dtl_ba_rows[] = array('id_permohonan'=>$row['fk_permohonan']);
											}
										}										

										$i = 0;
										foreach($dtl_ba_rows as $row)
										{
											$i++;
											echo "
											<tr id='row-".$i."'>												
												<td>
												<select name='permintaan_perforasi".$i."' id='permintaan_perforasi".$i."' class='form-control' required>
													<option value=''>== No. SKRD - Nama Rekening ==</option>";
													foreach($arr_perforasi as $opt){
														$selected = ($row['id_permohonan']==$opt['id_permohonan']?'selected':'');
														echo "<option value='".$opt['id_permohonan']."' ".$selected.">".$opt['no_skrd']." - ".$opt['nm_rekening']."</option>";
													}
												echo "</select>
												</td><td>";
												if($i>1)
												{
													echo "<button type='button' id='delete_dtl_ba_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_dtl_ba_row('".$i."');\"><i class='fa fa-trash-o'></i></button>";
												}
												echo "</td>
											</tr>";
										}
										?>										
									</tbody>
									<tfoot>
										<tr>											
											<td colspan="2" align="left"><a href="javascript:;" onclick="add_dtl_ba_row();"><i class="fa fa-plus"></i> Tambah Baris</a></td>
										</tr>							
									</tfoot>
								</table>
								<input type="hidden" id="n_dtl_ba_row" name="n_dtl_ba_row" value="<?=$i;?>"/>
								</table>
							</div>
						</div>
					</section>
				</div>				
		</fieldset>

		<footer>
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="button" class="btn btn-default" id="close-modal-form" data-dismiss="modal">Batal</button>
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
                               .enable_pnotify()
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	    function delete_dtl_ba_row(order_num)
	    {	    	
	    	var $tr = $('#dtl_ba-tbody > tr');
	    	$tr.remove('#row-'+order_num);	    	
	    }

	    function add_dtl_ba_row(){
	    	
	    	var perforasi_opts = "<?=$perforasi_opts;?>";

	    	var $tbody = $('#dtl_ba-tbody'), $lc_tbody = $('#dtl_ba-tbody tr:last-child'), $n_dtl_ba_row = $('#n_dtl_ba_row');
	    	var last_row_id = $lc_tbody.attr('id');
	    	
	    	x = last_row_id.split('-');
	    	last_order = x[1];
	    	new_order = parseInt(last_order)+1;

	    	new_row = "<tr id='row-"+new_order+"'>"+	    			  
	    			  "<td><select name='permintaan_perforasi"+new_order+"' id='permintaan_perforasi"+new_order+"' class='form-control' required>"+
	    			  perforasi_opts+"</select></td>"+
					  "<td><button type='button' id='delete_dtl_ba_row"+new_order+"' class='btn btn-default btn-xs' onclick=\"delete_dtl_ba_row('"+new_order+"');\"><i class='fa fa-trash-o'></i></button></td>"+
	    			  "</tr>";
	    	
	    	$n_dtl_ba_row.val(new_order);
	    	$tbody.append(new_row);
	    }

	    function init_jquery_plugin(){
	    	$(".datepicker").datepicker(
			{ 
				dateFormat: 'dd-mm-yy',
				prevText: '<i class="fa fa-chevron-left"></i>',
		    	nextText: '<i class="fa fa-chevron-right"></i>',
			});
			
			$("#tgl_berita_acara").mask('99-99-9999');
	    }

	    $(document).ready(function(){
			init_jquery_plugin();
		});
	    
	</script>
</div>