<!-- PRELOAD OBJECT -->
<div id="preloadAnimation" class="preload-wrapper">
    <div id="preloader_1">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<?php 

	require_once("inc/init.php");
	require_once("../lib/DML.php");
	require_once("../lib/user_controller.php");
	require_once("../helpers/date_helper.php");

	//instantiate objects
    $uc = new user_controller($db);
    $DML = new DML('app_ref_jenis_retribusi',$db);

    $uc->check_access();

    $x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);
	$fn = $_CONTENT_FOLDER_NAME[28];

	$readAccess = $uc->check_priviledge('read',$men_id);

	$curr_month = date('m');
	$curr_year = date('Y');
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-check-square-o"></i> 
				Penetapan
			<span>>  
				Daftar SKRD
			</span>
		</h1>
	</div>
		
</div>


<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content"></div>
	</div>
</div>
<!-- END MODAL -->

<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
<!-- END MODAL -->

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				
				<header>
					<span class="widget-icon"> <i class="fa fa-search"></i> </span>
					<h2>Pencarian SKRD</h2>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<form class="form-horizontal" id="form-daftar-laporan" action="ajax/<?=$fn;?>/dataview.php" method="POST">
							<input type="hidden" name="fn" value="<?=$fn;?>"/>
							<input type="hidden" name="tipe_laporan" id="tipe_laporan"/>
							
							<fieldset>
								
								<div class="form-group">
									<label class="control-label col-md-2" for="src_status">Status</label>
									<div class="col-md-3">
						                <select name="src_status" id="src_status" class="form-control" required>
						                	<option value="1">Belum ditetapkan</option>
						                	<option value="2">Sudah ditetapkan</option>
						                	<option value="3">Terbayar</option>
						                </select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2" for="src_tipe">Tipe Retribusi</label>
									<div class="col-md-3">
						                <input type="radio" name="src_tipe" id="src_tipe1" value="1" checked/> Non Karcis&nbsp;&nbsp;
						                <input type="radio" name="src_tipe" id="src_tipe1" value="2"/> Karcis
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2">Periode SKRD</label>
									<div class="col-md-2">
						                <input type="text" name="src_tgl_skrd_awal" id="src_tgl_skrd_awal" class="form-control datepicker" data-dateformat="dd-mm-yy" />
									</div>
									<div class="col-md-2">
						                <input type="text" name="src_tgl_skrd_akhir" id="src_tgl_skrd_akhir" class="form-control datepicker" data-dateformat="dd-mm-yy" />
									</div>
								</div>								

							</fieldset>
							
							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">
									<?php
									if($readAccess)
									{
										echo "
										<button class='btn btn-default' type='submit' onclick=\"fill_report_type('1');\"><i class='fa fa-print'></i> Cetak</button>
										<button class='btn btn-success' type='submit' onclick=\"fill_report_type('2');\"><i class='fa fa-file-pdf-o'></i> PDF</button>";
									}
									else
									{
										echo "<button class='btn btn-default' type='button' onclick=\"alert('Anda tidak memiliki hak akses untuk melihat laporan');\"><i class='fa fa-print'></i> Cetak</button>
										<button class='btn btn-success' type='button' onclick=\"alert('Anda tidak memiliki hak akses untuk melihat laporan');\"><i class='fa fa-file-pdf-o'></i> PDF</button>";
									}
									?>
									</div>
								</div>
							</div>

						</form>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

	<!-- end row -->

</section>
<!-- end widget grid -->


<!-- widget grid -->
<section id="widget-grid" class="">
	<div class="row" id="data-view">		
	</div>
</section>

<!-- widget grid -->
<section id="widget-grid" class="">
	<div class="row" id="data-view">

	</div>
</section>


<script type="text/javascript">

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */	

	pageSetUp();	

	var fn = "<?php echo $fn; ?>";

	var form_id1 = 'form-daftar-laporan';

    var $search_form1 = $('#'+form_id1);
    var stat = $search_form1.validate({
		// Rules for form validation			

		// Do not change code below
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

    $search_form1.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#data-view')
                       	   .set_loading('#preloadAnimation')                           	   
                           .set_form($search_form1)
                           .disable_pnotify()
                           .submit_ajax('');
            return false;
        }
    });    
    
    function fill_report_type(type)
    {    	
    	$('#tipe_laporan').val(type);
    }

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 */
	
	// PAGE RELATED SCRIPTS
	
	// pagefunction	
	var pagefunction = function() {
		//console.log("cleared");
		
		/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
		*/	

		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};


		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#src_tgl_skrd_awal").mask('99-99-9999');
		$("#src_tgl_skrd_akhir").mask('99-99-9999');
		
	};

	

	// load related plugins
	
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});


</script>
