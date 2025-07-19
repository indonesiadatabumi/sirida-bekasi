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
require_once("../helpers/date_helper.php");

//instantiate objects
$uc = new user_controller($db);

$uc->check_access();

$x_uri = explode('/', $_SERVER['REQUEST_URI']);
$uri = $x_uri[count($x_uri) - 1];

$men_id = $uc->get_menu_id('url', 'ajax/' . $uri);
$fn = $_CONTENT_FOLDER_NAME[4];

$curr_month = date('m');
$curr_year = date('Y');
?>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <h1 class="page-title txt-color-blueDark">
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-file-text-o"></i>
            Pendataan
            <span>>
                Upload Data Pasar
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

                <header>
                    <span class="widget-icon"> <i class="fa fa-file-text-o"></i> </span>
                    <h2>Form Upload</h2>
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

                        <form class="form-horizontal" id="form-upload-data-pasar" onsubmit="return confirm('Apakah anda yakin?');" action="ajax/upload-data/proses-upload-pasar.php" method="POST" enctype="multipart/form-data">
                            <fieldset>

                                <div class="form-group">

                                    <div class="form-group">
                                        <label class="control-label col-md-2" for="file_data">File Pendataan </label>
                                        <div class="col-md-3">
                                            <input type="file" name="fileExcel" id="fileExcel" class="form-control" />
                                        </div>
                                        <div class="col-md-2 control-label">[<a href="ajax/upload-data/contoh data upload.xlsx">Contoh format upload data pasar</a>]</div>
                                    </div>

                            </fieldset>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary" type="submit" name="submit">
                                            <i class="fa fa-file"></i>
                                            Upload Data
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