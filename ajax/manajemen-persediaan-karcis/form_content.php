<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	
	$DML = new DML('app_persediaan_benda_berharga',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	$fk_permohonan = $_GET['fk_permohonan'];

	$inTransCode = '1';
	$outTransCode = '2';

	$id_name = 'id_persediaan';
    $id_value = ($act=='edit'?$_GET['id']:'');

	$arr_field = array('no_persediaan','tgl_persediaan','keterangan','blok_keluar','blok_masuk','no_awal',
    					'no_akhir','sisa_blok','jumlah_lembar','nilai_uang','jenis_transmisi');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);

    $cond = "fk_permohonan='".$fk_permohonan."'";
    $prevDataCond = $cond;
    $prevNumCond = $cond;

    if($act=='add'){
    	$no_persediaan = $global->get_ticketInventoryNum($fk_permohonan);
    }else{
    	$no_persediaan = $curr_data['no_persediaan'];
    	$prevDataCond .= " AND no_persediaan < '".$curr_data['no_persediaan']."'";
    	$prevNumCond .= " AND no_persediaan < '".$curr_data['no_persediaan']."'";
    }

    $sql = "SELECT a.sisa_blok,a.jumlah_lembar,a.nilai_uang,b.isi_per_blok,b.nilai_per_lembar,b.nilai_total_perforasi,b.jumlah_blok 
    		FROM app_persediaan_benda_berharga as a 
    		LEFT JOIN (SELECT isi_per_blok,nilai_per_lembar,nilai_total_perforasi,jumlah_blok,id_permohonan FROM app_permohonan_karcis) as b 
    		ON (a.fk_permohonan=b.id_permohonan) WHERE ".$prevDataCond." ORDER BY no_persediaan DESC";

    $prevData = $db->getRow($sql);
    $prevInNum = $db->getRow("SELECT no_awal,no_akhir FROM app_persediaan_benda_berharga WHERE ".$prevNumCond." AND jenis_transmisi='".$inTransCode."' ORDER BY no_persediaan DESC");
    $prevOutNum = $db->getRow("SELECT no_awal,no_akhir FROM app_persediaan_benda_berharga WHERE ".$prevNumCond." AND jenis_transmisi='".$outTransCode."' ORDER BY no_persediaan DESC");
	

    $prevInFirstNum = (count($prevInNum)>0?$prevInNum['no_awal']:'');
    $prevInLastNum = (count($prevInNum)>0?$prevInNum['no_akhir']:'');

    $prevOutFirstNum = (count($prevOutNum)>0?$prevOutNum['no_awal']:'');
    $prevOutLastNum = (count($prevOutNum)>0?$prevOutNum['no_akhir']:'');

    $form_id = 'ticket-inventory-form';

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
		
		$("#tgl_persediaan").mask('99-99-9999');		
		
	});
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Persediaan Benda Berharga</h4>

</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">		
		<input type="hidden" name="id_persediaan" value="<?=$id_value?>"/>
		<input type="hidden" name="fk_permohonan" value="<?=$fk_permohonan?>"/>
		<input type="hidden" name="no_persediaan" value="<?=$no_persediaan;?>"/>
		<input type="hidden" name="isi_per_blok" value="<?=$prevData['isi_per_blok'];?>"/>
		<input type="hidden" name="nilai_per_lembar" value="<?=$prevData['nilai_per_lembar'];?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
		<input type="hidden" name="fn" value="<?=$fn?>"/>
		<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					
					<section>
						<div class="row">
							<label class="label col col-1"></label>
							<div class="col-md-10">

								<table class="table table-bordered">

									<thead>
										<tr><td colspan="4" align="center"><b>Data perforasi</b></td></tr>
										<tr><th>Jum. Blok</th><th>Isi Per Blok</th><th>Nilai Per Lembar</th><th>Total Nilai</th></tr>
									</thead>
									<tbody>
										<?php
										echo "
										<tr>
										<td align='right'><input type='text' id='jumlah_blok' value='".number_format($prevData['jumlah_blok'])."' style='text-align:right;width:100%;' readonly/></td>
										<td align='right'><input type='text' id='isi_per_blok' value='".number_format($prevData['isi_per_blok'])."' style='text-align:right;width:100%;' readonly/></td>
										<td align='right'><input type='text' id='nilai_per_lembar' value='".number_format($prevData['nilai_per_lembar'])."' style='text-align:right;width:100%;' readonly/></td>
										<td align='right'><input type='text' id='total_perforasi' value='".number_format($prevData['nilai_total_perforasi'])."' style='text-align:right;width:100%;' readonly/></td>
										</tr>";
										?>
									</tbody>
								</table>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Persediaan Sebelumnya</label>
							<div class="col-md-6">
								<table class="table table-bordered">
									<thead>
										<tr><th>Sisa Blok</th><th>Jumlah Lembar</th><th>Nilai Uang</th></tr>
									</thead>
									<tbody>
										<?php
										echo "
										<tr>
										<td align='right'><input type='text' id='prev_sisa_blok' value='".number_format($prevData['sisa_blok'])."' style='text-align:right;width:100%;' readonly/></td>
										<td align='right'><input type='text' id='prev_jumlah_lembar' value='".number_format($prevData['jumlah_lembar'])."' style='text-align:right;width:100%;' readonly/></td>
										<td align='right'><input type='text' id='prev_nilai_uang' value='".number_format($prevData['nilai_uang'])."' style='text-align:right;width:100%;' readonly/></td>
										</tr>";
										?>
									</tbody>
								</table>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. Persediaan<font color="red"></font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" id="tgl_persediaan" name="tgl_persediaan" value="<?=indo_date_format(($act=='add'?$_CURR_DATE:$curr_data['tgl_persediaan']),'shortDate')?>" class="form-control datepicker" required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Keterangan<font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="keterangan" id="keterangan" class="form-control" value="<?=$curr_data['keterangan'];?>" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">&nbsp;</label>
							<div class="col col-3">
								<label class="radio">
									<input type="radio" name="jenis_transmisi" id="jenis_transmisi"  
										value="<?=$outTransCode;?>" onclick="control_transmission_input(<?=$outTransCode;?>);" <?=($act=='add'?'checked':($curr_data['jenis_transmisi']==$outTransCode?'checked':''))?>/><i></i>Blok Keluar
								</label><br />
								<label class="radio">
									<input type="radio" name="jenis_transmisi" id="jenis_transmisi" 
										value="<?=$inTransCode;?>" onclick="control_transmission_input(<?=$inTransCode;?>);" <?=($act=='add'?'':($curr_data['jenis_transmisi']==$inTransCode?'checked':''))?>/><i></i>Blok Masuk
								</label>
							</div>
							<div class="col col-2">
								<label class="input">
								<input type="text" name="blok_keluar" id="blok_keluar" style="text-align:right;" onkeypress="return only_number(event,this)" 
								onkeyup="count_curr_inventory(<?=$outTransCode;?>,this.value)" value="<?=($curr_data['blok_keluar']!='0'?$curr_data['blok_keluar']:'');?>" class="form-control" <?=($act=='add'?'required':($curr_data['jenis_transmisi']==$outTransCode?'required':'disabled'))?>/>
								</label><br />
								<label class="input">									
								<input type="text" name="blok_masuk" id="blok_masuk" style="text-align:right;" onkeypress="return only_number(event,this)" 
								onkeyup="count_curr_inventory(<?=$inTransCode;?>,this.value)" value="<?=($curr_data['blok_masuk']!='0'?$curr_data['blok_masuk']:'');?>" class="form-control" <?=($act=='add'?'disabled':($curr_data['jenis_transmisi']==$inTransCode?'required':'disabled'))?>/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">No. Awal - No. Akhir<font color="red">*</font></label>
							<div class="col col-2">
								<label class="input">
									<input type="text" name="no_awal" id="no_awal" class="form-control" value="<?=$curr_data['no_awal'];?>" style="text-align:right" required/>
								</label>
							</div>
							<div class="col col-2">
								<label class="input">
									<input type="text" name="no_akhir" id="no_akhir" class="form-control" value="<?=$curr_data['no_akhir'];?>" style="text-align:right" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Persediaan Saat Ini</label>
							<div class="col-md-6">
								<table class="table table-bordered">
									<thead>
										<tr><th>Sisa Blok</th><th>Jumlah Lembar</th><th>Nilai Uang</th></tr>
									</thead>
									<tbody>
										<?php
										echo "<tr><td align='right'>
										<input type='text' name='sisa_blok' id='curr_sisa_blok' value='".number_format(($act=='add'?$prevData['sisa_blok']:$curr_data['sisa_blok']))."' style='text-align:right;width:100%' readonly/></td>
										<td align='right'><input type='text' name='jumlah_lembar' id='curr_jumlah_lembar' value='".number_format(($act=='add'?$prevData['jumlah_lembar']:$curr_data['jumlah_lembar']))."' style='text-align:right;width:100%' readonly/></td>
										<td align='right'><input type='text' name='nilai_uang' id='curr_nilai_uang' value='".number_format(($act=='add'?$prevData['nilai_uang']:$curr_data['nilai_uang']))."' style='text-align:right;width:100%' readonly/></td>
										</tr>";
										?>
									</tbody>
								</table>								
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
                               .enable_pnotify()
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	    function count_curr_inventory(type,val){

	    	var prevInLastNum = parseInt(<?=(!empty($prevInLastNum) && !is_null($prevInLastNum)?$prevInLastNum:0);?>),
	    		 prevOutLastNum = parseInt(<?=(!empty($prevOutLastNum) && !is_null($prevOutLastNum)?$prevOutLastNum:0);?>),
	    		 prevRemainBlock = parseInt(<?=$prevData['sisa_blok'];?>),
	    		 prevNumbersPage = parseInt(<?=$prevData['jumlah_lembar'];?>), 
	    		 prevTicketValue = parseInt(<?=$prevData['nilai_uang'];?>),
	    		 valuePerPage = parseInt(<?=$prevData['nilai_per_lembar'];?>);
	    		 numbersPerBlock = parseInt(<?=$prevData['isi_per_blok'];?>);
	    	
	    	var outTransCode = <?=$outTransCode;?>,inTransCode = <?=$inTransCode;?>;

    		var currFirstNum = (type==inTransCode?"<?=$prevInLastNum;?>":"<?=$prevOutLastNum;?>"), 
    			currLastNum = currFirstNum,
    			currRemainBlock = prevRemainBlock, currNumbersPage = prevNumbersPage, currTicketValue = prevTicketValue;
    		

    		var $firstNum = $('#no_awal'),$lastNum = $('#no_akhir'),$remainBlock = $('#curr_sisa_blok'),$numbersPage = $('#curr_jumlah_lembar'),$ticketValue = $('#curr_nilai_uang');

    		if(val!='' && val!='0')
    		{
	    		var lastNum = (type==inTransCode?prevInLastNum:prevOutLastNum);
		    	currFirstNum = lastNum + 1;
		    	currLastNum = lastNum + (parseInt(val)*numbersPerBlock);

		    	if(type==inTransCode)
		    		currRemainBlock = prevRemainBlock + parseInt(val);		    		
		    	else
		    		currRemainBlock = prevRemainBlock - parseInt(val);
		    	
		    	currNumbersPage = currRemainBlock * numbersPerBlock;
		    	currTicketValue = currNumbersPage * valuePerPage;
		    }

	    	$firstNum.val((currFirstNum!=''?number_format(currFirstNum,0,'.',','):''));
	    	$lastNum.val((currLastNum!=''?number_format(currLastNum,0,'.',','):''));
	    	$remainBlock.val(number_format(currRemainBlock,0,'.',','));
	    	$numbersPage.val(number_format(currNumbersPage,0,'.',','));
	    	$ticketValue.val(number_format(currTicketValue,0,'.',','));

	    }

	    function control_transmission_input(type,init=false){
	    	var $bk = $('#blok_keluar'), $bm = $('#blok_masuk');
	    	var outTransCode = <?=$outTransCode;?>,inTransCode = <?=$inTransCode;?>;

	    	if(init)
	    	{
		    	$bk.val(<?=($curr_data['blok_keluar']!='0'?$curr_data['blok_keluar']:'');?>);
		    	$bm.val(<?=($curr_data['blok_masuk']!='0'?$curr_data['blok_masuk']:'');?>);
		    	trans_pack = "<?=($act=='add'?'':($curr_data['jenis_transmisi']==$inTransCode?$curr_data['blok_masuk']:$curr_data['blok_keluar']))?>";
		    }else{
		    	$bk.val('');
		    	$bm.val('');
		    	trans_pack = '';
		    }
	    	
	    	count_curr_inventory(type,trans_pack);
	    		    	
	    	$bk.attr('required',type==outTransCode);
			$bk.attr('disabled',type==inTransCode);
			$bm.attr('required',type==inTransCode);
			$bm.attr('disabled',type==outTransCode);

	    	if(type==inTransCode){
	    		$bk.addClass('disabled-bg');
				$bm.removeClass('disabled-bg');				
	    	}else{
				$bm.addClass('disabled-bg');
				$bk.removeClass('disabled-bg');
	    	}
	    }

	    <?php 
	    	echo "control_transmission_input(".($act=='add'?'2':$curr_data['jenis_transmisi']).",true);";
	    ?>
	</script>

</div>