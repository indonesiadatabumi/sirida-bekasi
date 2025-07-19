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
$fn = $_CONTENT_FOLDER_NAME[39];
// require_once($fn . "/list_sql.php");
require_once("../lib/user_controller.php");

//instantiate objects
$uc = new user_controller($db);

$uc->check_access();

$x_uri = explode('/', $_SERVER['REQUEST_URI']);
$uri = $x_uri[count($x_uri) - 1];

$men_id = $uc->get_menu_id('url', 'ajax/' . $uri);

$readAccess = $uc->check_priviledge('read', $men_id);
$addAccess = $uc->check_priviledge('add', $men_id);
$editAccess = $uc->check_priviledge('edit', $men_id);
$deleteAccess = $uc->check_priviledge('delete', $men_id);

// $list_of_data = $db->Execute($list_sql);
// if (!$list_of_data)
// 	print $db->ErrorMsg();
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-pencil-square-o"></i>
			Penagihan
			<span>>
				Rekam STRD
			</span>
		</h1>
	</div>

</div>

<section id="widget-grid" class="">


	<!-- START ROW -->

	<div class="row">

		<!-- NEW COL START -->
		<article class="col-sm-12 col-md-12 sortable-grid ui-sortable">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
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
				<header role="heading" class="ui-sortable-handle">
					<h2>Form STRD </h2>

					<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
				</header>

				<!-- widget div-->
				<div role="content">

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<?php include($fn . "/form_content.php"); ?>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->


		</article>
		<!-- END COL -->

	</div>

	<!-- END ROW -->

</section>