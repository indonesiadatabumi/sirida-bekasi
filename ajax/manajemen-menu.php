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
	$fn = $_CONTENT_FOLDER_NAME[22];
	require_once($fn."/list_sql.php");		
	require_once("../lib/user_controller.php");
	require_once("../lib/menu_management.php");
	
	//instantiate objects
    $uc = new user_controller($db);
    $menu_obj = new menu_management($db);

    $uc->check_access();

	$x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);

    $readAccess = $uc->check_priviledge('read',$men_id);
    $addAccess = $uc->check_priviledge('add',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();
?>
<style type="text/css">
	nav.for-editing {
	  font-family: Helvetica, Arial, "Lucida Grande", sans-serif;
	  line-height: 1.5;
	  margin: 0; /*for display only*/
	  width: 100%;
	  -webkit-box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
	     -moz-box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
	          box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
	}
	 
	.menu-item {
	  background: #fff;
	  width: 100%;
	}


	/*Menu Header Styles*/
	.menu-item h4 {
	  border-bottom: 1px solid rgba(0,0,0,0.3);
	  border-top: 1px solid rgba(255,255,255,0.2);
	  color: #fff;
	  font-size: 15px;
	  font-weight: 500;
	  padding: 7px 12px;
	  
	  background-color:#4c4f53;
	}
	.menu-item h4:hover {  
	  background: #424344; /* Old browsers */
	}
	.menu-item h4 span {
	  color: white;
	  display: block;
	  text-decoration: none;
	  width: 100%;
	}

	/*ul Styles*/
	.menu-item ul {	  
	  background: #fff;
	  font-size: 13px;
	  line-height: 30px;
	  list-style-type: none;
	  padding: 0px;
	}
		
	.menu-item ul li {
	  margin-left: 20px;
	  text-decoration: none;
	  color: black;
	  display: block;	  
	}

	.menu-item ul li a{
		margin:0;
		padding:0;
	}

	.menu-item ul li span{
		margin-left:5px;
		display:block;

	}

	.menu ul li.has-submenu > ul{
		display:block;

	}
	 
	/*li Styles*/
	.menu-item li {
	  border-bottom: 1px solid #eee;
	}

	.menu-item li:last-child{
	 	border-bottom:none;
	}

	.menu-item li:hover {
	  background: #eee;
	}

	
	.menu-item a:hover{cursor:pointer;}
</style>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-gear"></i> 
				Setting
			<span>>  
				Manajemen Menu
			</span>
		</h1>
	</div>
		
</div>


<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
<!-- END MODAL -->

<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
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
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Daftar Menu | </h2>
					<h2>
						<?php	
						if($addAccess)
							echo "<a href='ajax/".$fn."/form_content.php?act=add&fn=".$fn."&men_id=".$men_id."' class='btn btn-default btn-xs' data-toggle='modal' data-target='#remoteModal'>";
						else
							echo "<a href='javascript:;' onclick=\"alert('Anda tidak memiliki hak akses untuk menambah data !');\" class='btn btn-default btn-xs'>";
						
						echo "<i class='fa fa-plus'></i> Tambah </a>";
						?>
					</h2>					
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->						
					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body" id="list-of-data">
						<?php 
							include_once $fn."/list_of_data.php"; 
						?>
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
    function get_villages_npwrd(district)
    {

    	$.ajax({
            type:'POST',
            url:'ajax/'+fn+'/villages_npwrd.php',
            data:'district='+district,
            beforeSend:function(){                    
                $('#preloadAnimation').show();
            },
            complete:function(){                    
                $('#preloadAnimation').hide();
            },
            success:function(data)
            {                    
                check=/ERROR/;
                            
                if(data.match(check))
                {
                    alert(data);
                    return true;
                }
                else
                {                	
                    var result_array    	= data.split('|%&%|');
                    var kelurahan      		= result_array[0];
                    var npwrd     			= result_array[1];
                 	
                    $('#kelurahan').html(kelurahan);
                    $('#npwrd').val(npwrd);
                }
            }
        });
        
    }    


    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('ajax/'+fn+'/manipulating.php').set_plugin_datatable(true).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content('#list-of-data').enable_pnotify().update_ajax('menghapus data!');
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


		/* TABLETOOLS */
		$('#data-table-jq').dataTable({
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
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});


</script>
