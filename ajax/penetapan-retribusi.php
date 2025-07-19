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
	require_once("../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

	$x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);
	$fn = $_CONTENT_FOLDER_NAME[6];
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-check-square-o"></i> 
				Penetapan
			<span>>  
				Penetapan Retribusi
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
					<h2>Pencarian Retribusi WR</h2>
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

						<form class="form-horizontal" id="form-pencarian-retribusi-wr" action="ajax/<?=$fn;?>/dataview.php" method="POST">
							<input type="hidden" name="fn" value="<?=$fn;?>"/>
							<input type="hidden" name="men_id" value="<?=$men_id;?>"/>
							<fieldset>

								<div class="form-group">
									
									<label class="control-label col-md-2" for="npwrd">NPWRD <font color="red">*</font></label>
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
									
									<!-- MODAL PLACE HOLDER -->
									<div class="modal fade" id="browseModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content"></div>
										</div>
									</div>
									<!-- END MODAL -->
								</div>

								<div class="form-group">
									
									<label class="control-label col-md-2" for="nm_wp_wr">Nama WR</label>
									<div class="col-md-4">
						                <input type="text" name="nm_wp_wr" id="nm_wp_wr" class="form-control" readonly/>
									</div>
									
								</div>

								<!-- <div class="form-group">
									<label class="control-label col-md-2" for="type_retribusi">Jenis Retribusi</label>
									<div class="col-md-3">
						                <select id="type_retribusi" name="type_retribusi" class="form-control">
						                	<option value="1">SKRD</option>
						                	<option value="2">Karcis</option>
						                </select>
									</div>									
								</div> -->

								<div class="form-group">
									
									<label class="control-label col-md-2" for="nm_retribusi">Nama & Tahun Retribusi</label>
									<div class="col-md-6">
						                <input type="text" name="nm_retribusi" id="nm_retribusi" class="form-control" readonly/>
						                <input type="hidden" name="id_permohonan" id="id_permohonan"/>
									</div>
									<div class="col-md-1">
										<input type="text" name="tahun_retribusi" id="tahun_retribusi" value="<?=$_CURR_YEAR;?>" class="form-control"/>
									</div>
									
								</div>	
								
							</fieldset>
														
							
							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">										
										<button class="btn btn-primary" type="submit">
											<i class="fa fa-eye"></i>
											Submit
										</button>
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
    
    function search_wr(key)
    {    
        ajax_manipulate.reset_object();
        var data_ajax = new Array('key='+key);                
        ajax_manipulate.set_plugin_datatable(false).set_url('ajax/'+fn+'/get_wr_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#wr_list_tbody').request_ajax();
    }
    
    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('ajax/'+fn+'/manipulating.php').set_plugin_datatable(true).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content('#list-of-data').enable_pnotify().update_ajax('menghapus penetapan retribusi!');
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
		var form_id1 = 'form-pencarian-retribusi-wr';

	    var $search_form1 = $('#'+form_id1);
	    
	    $search_form1.bootstrapValidator({
				feedbackIcons : {
					valid : 'glyphicon glyphicon-ok',
					invalid : 'glyphicon glyphicon-remove',
					validating : 'glyphicon glyphicon-refresh'
				},
				submitButtons: 'button[type="submit"]',				
			})
			.on('success.form.bv',function(e){

					e.preventDefault();
					
					 // Get the form instance
		            var $form = $(e.target);
		            // Get the BootstrapValidator instance
		            var bv = $form.data('bootstrapValidator');

				   	ajax_manipulate.reset_object();
		            ajax_manipulate.set_plugin_datatable(true)
	                           .set_content('#data-view')
	                       	   .set_loading('#preloadAnimation')
	                           .set_form($search_form1)
	                           .disable_pnotify()
	                           .submit_ajax('');

	                $("button[type='submit']").prop('disabled',false);
		        	return false;
			});
	    
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


		/* TABLETOOLS */
		$('#data-table-jq1').dataTable({
			"oLanguage": {
                "sSearch": "Search :"
                },
                "aoColumnDefs": [
                    {
                        'bSortable': false,
                        'aTargets': [0]
                    } //disables sorting for column one
                ],
                'iDisplayLength': 10,
                "sPaginationType": "full_numbers"
		});
		
		/* END TABLETOOLS */

	};

	// load related plugins
	
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", function(){
						loadScript("js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction)
					});
				});
			});
		});
	});



</script>
