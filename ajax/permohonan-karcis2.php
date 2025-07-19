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
	require_once("../helpers/date_helper.php");
	require_once("../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

	$x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);
    $fn = $_CONTENT_FOLDER_NAME[3];
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-pencil-square-o"></i> 
				Pendaftaran
			<span>>  
				Permohonan Karcis
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
					<h2>Input Data Permohonan Karcis</h2>
				</header>
				<div class="widget-body">
					<form class="form-horizontal" id="form-pencarian-ketetapan-retribusi" action="ajax/<?=$fn;?>/dataview.php" method="POST">
						<input type="hidden" name="fn" value="<?=$fn;?>"/>
						<h3>No. Permohonan : 240805973</h3>							
						<fieldset>	
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-2 col-form-label">Pilih Jukir</label>
								<div class="col-sm-4">
								<select id="type_retribusi" name="type_retribusi" class="form-control">
									<option value="1">- Pilih Jukir -</option>
									<option value="1">Tes</option>
									<option value="2">Tes2</option>
								</select>
								</div>
							</div>					

							<div class="table-responsive">
								<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
									<thead>
										<th>Jenis Transmisi</th>
										<th>Jumlah Lembar</th>
										<th>Tarif</th>
										<th>Nilai Uang</th>
										<th>Checked</th>
									</thead>
									<tbody>
										<tr>
											<td>Sepeda Motor</td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
										</tr>
										<tr>
											<td>Mobil</td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
										</tr>
										<tr>
											<td>Bus Mini</td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
										</tr>
										<tr>
											<td>Bus Besar</td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
											<td><input type="text" class="form-control" id="inputEmail3"></td>
										</tr>
									</tbody>
								</table>
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