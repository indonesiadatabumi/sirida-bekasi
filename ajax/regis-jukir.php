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
	$fn = $_CONTENT_FOLDER_NAME[1];
	require_once($fn."/list_sql.php");		
	require_once("../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

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

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-pencil-square-o"></i> 
				Pendaftaran
			<span>>  
				Pendaftaran Jukir
			</span>
		</h1>
	</div>
		
</div>

<!-- widget grid -->
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-parking"></i> </span>
					<h2>Input Data Jukir</h2>
				</header>
				<div class="widget-body">
					<form class="form-horizontal" id="form-pencarian-ketetapan-retribusi" action="ajax/<?=$fn;?>/dataview.php" method="POST">
						<input type="hidden" name="fn" value="<?=$fn;?>"/>							
						<fieldset>	
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">NIK</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>					

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">NPWRD</label>
								<div class="col-sm-1">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
								<div class="col-sm-1">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
								<div class="col-sm-2">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
								<div class="col-sm-2">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
								<div class="col-sm-2">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>	

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Nama Pengelola Parkir</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>
							
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Nama Lengkap</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>
							
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Tempat / Tanggal Lahir</label>
								<div class="col-sm-2">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
								<div class="col-sm-2">
								<input type="date" class="form-control" id="inputEmail3">
								</div>
							</div>

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Alamat</label>
								<div class="col-sm-4">
								<textarea class="form-control is-invalid" name="" id="" cols="30" rows="5"></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">No Hp</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">No Rekening</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
								</div>
							</div>

							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Lokasi Parkir</label>
								<div class="col-sm-4">
								<input type="email" class="form-control" id="inputEmail3">
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
			</div>
		</article>
	</div>
</section>
<!-- end widget grid -->