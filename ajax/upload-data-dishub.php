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
                Upload Data Dishub
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

                        <form class="form-horizontal" id="form-upload-data-dishub" action="ajax/upload-data/proses-upload.php" method="POST" enctype="multipart/form-data">
                            <fieldset>

                                <div class="form-group">
                                    <label class="control-label col-md-2" for="bln_retribusi">Masa Retribusi</label>
                                    <div class="col-md-2">
                                        <select name="bln_retribusi" id="bln_retribusi" class="form-control">
                                            <?php

                                            for ($i = 1; $i <= 12; $i++) {
                                                $selected = ($i == $curr_month ? 'selected' : '');
                                                echo "<option value='" . $i . "' " . $selected . ">" . get_monthName($i) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" name="tahun_retribusi" id="tahun_retribusi" value="<?= $_CURR_YEAR; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2" for="tgl-skrd">Tanggal SKRD </label>
                                    <div class="col-md-2">
                                        <label class="input">
                                            <input type="text" name="tgl_skrd" id="tgl_skrd" value="<?= indo_date_format($_CURR_DATE, 'shortDate') ?>" class="form-control datepicker" required />
                                        </label>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="dasar-hukum">Dasar Hukum </label>
                                    <div class="col-md-3">
                                        <select name="dasar_pengenaan" id="dasar_pengenaan" class="form-control">
                                            <?php
                                            $sql = "SELECT id_jenis_retribusi, dasar_hukum_pengenaan FROM app_ref_jenis_retribusi WHERE kd_rekening='4120128' ORDER BY id_jenis_retribusi ASC";
                                            $result = $db->Execute($sql);
                                            while ($row = $result->FetchRow()) {
                                                echo "<option value='" . $row['id_jenis_retribusi'] . "' selected>" . $row['dasar_hukum_pengenaan'] . "</option>";
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="jenis-retribusi">Jenis Retribusi </label>
                                    <div class="col-md-3">
                                        <select name="jns_retribusi" id="jns_retribusi" class="form-control">
                                            <option value="" selected>--</option>
                                            <?php
                                            $sql = "SELECT kd_rekening,jenis_retribusi FROM app_ref_jenis_retribusi WHERE (kd_rekening='4120125' OR kd_rekening='4120127' OR kd_rekening='4120128') ORDER BY id_jenis_retribusi ASC";
                                            $result2 = $db->Execute($sql);

                                            while ($row2 = $result2->FetchRow()) {
                                                echo "<option value='" . $row2['kd_rekening'] . "'>" . $row2['jenis_retribusi'] . "</option>";
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                -->
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="file_data">File Pendataan </label>
                                    <div class="col-md-3">
                                        <input type="file" name="fileExcel" id="fileExcel" class="form-control" />
                                    </div>
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