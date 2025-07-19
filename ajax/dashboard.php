<?php 
	require_once("inc/init.php"); 
	require_once("../helpers/date_helper.php");

	$curr_month = $_CURR_MONTH;
	$prev_month = ($curr_month>1?$curr_month - 1:12);
	$curr_year = $_CURR_YEAR;
	$prev_year = ($prev_month==12?$_CURR_YEAR-1:$_CURR_YEAR);

	$sql = "SELECT SUM(total_bayar) as total_retribusi FROM app_pembayaran_retribusi";
	
	$tot1 = $db->getOne($sql." WHERE to_char(tgl_pembayaran,'YYYY-MM-DD')='".$_CURR_DATE."'");
	$tot2 = $db->getOne($sql." WHERE EXTRACT(MONTH FROM tgl_pembayaran)=".$curr_month);
	$tot3 = $db->getOne($sql." WHERE EXTRACT(YEAR FROM tgl_pembayaran)=".$curr_year);
	$tot4 = $db->getOne($sql." WHERE EXTRACT(MONTH FROM tgl_pembayaran)=".$prev_year);

	// $barData = "[";
	// $s = false;
	// for($i=1;$i<=12;$i++)
	// {
	// 	$sql = "SELECT SUM(total_bayar) as total_retribusi FROM app_pembayaran_retribusi WHERE EXTRACT(MONTH FROM tgl_pembayaran)=".$i;

	// 	$tot_bayar = $db->getOne($sql);
	// 	$tot_bayar = (is_null($tot_bayar)?0:$tot_bayar/1000000);

	// 	$barData .= ($s?',':'').(is_null($tot_bayar)?0:$tot_bayar);
	// 	$s=true;
	// }
	
	// $barData .= "]";

	$lineData_current = "[";
	$s = false;
	for($i=1;$i<=12;$i++)
	{
		$sql = "SELECT SUM(total_bayar) as total_retribusi FROM app_pembayaran_retribusi WHERE EXTRACT(MONTH FROM tgl_pembayaran)=".$i." AND EXTRACT(YEAR FROM tgl_pembayaran)='2024'";

		$tot_bayar = $db->getOne($sql);
		$tot_bayar = (is_null($tot_bayar)?0:$tot_bayar/1000000);

		$lineData_current .= ($s?',':'').(is_null($tot_bayar)?0:$tot_bayar);
		$s=true;
	}
	
	$lineData_current .= "]";

	$lineData_last = "[";
	$s = false;
	for($i=1;$i<=12;$i++)
	{
		$sql = "SELECT SUM(total_bayar) as total_retribusi FROM app_pembayaran_retribusi WHERE EXTRACT(MONTH FROM tgl_pembayaran)=".$i." AND EXTRACT(YEAR FROM tgl_pembayaran)='2023'";

		$tot_bayar = $db->getOne($sql);
		$tot_bayar = (is_null($tot_bayar)?0:$tot_bayar/1000000);

		$lineData_last .= ($s?',':'').(is_null($tot_bayar)?0:$tot_bayar);
		$s=true;
	}
	
	$lineData_last .= "]";

	$sql_prevMonth = "SELECT SUM(a.total_bayar) as total_retribusi FROM app_pembayaran_retribusi as a 
					WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))";
	$totPrevMonth = $db->getOne($sql_prevMonth);

	$sql_currMonth = "SELECT SUM(a.total_bayar) as total_retribusi FROM app_pembayaran_retribusi as a 
					WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))";
	$totCurrMonth = $db->getOne($sql_currMonth);

	$sql_prevMonth2 = "SELECT SUM(a.total_retribusi) as total_retribusi FROM app_pengembalian_karcis as a 
					WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pengembalian)=".$curr_year."))";
	$totPrevMonth2 = $db->getOne($sql_prevMonth2);

	$sql_currMonth2 = "SELECT SUM(a.total_retribusi) as total_retribusi FROM app_pengembalian_karcis as a 
					WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pengembalian)=".$curr_year."))";
	$totCurrMonth2 = $db->getOne($sql_currMonth2);
?>

<style type="text/css">
	h5{margin-top:5px!important;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> Penerimaan Retribusi</span></h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> Penerimaan Hari Ini <span class="txt-color-blue">Rp. <?=number_format($tot1);?></span></h5>
			</li>
			<li class="sparks-info">
				<h5> Penerimaan Bulan Ini <span class="txt-color-orange">Rp. <?=number_format($totCurrMonth);?></span></h5>
			</li>
			<li class="sparks-info">
				<h5> Penerimaan Tahun Ini <span class="txt-color-greenDark">Rp. <?=number_format($tot3);?></span></h5>
			</li>
		</ul>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		<article class="col-sm-6">
			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
					<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
					<h2>Perbandingan Penerimaan Retribusi Per Bulan</h2>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

						
					</div>
					<!-- end widget edit box -->
					<small>Dalam Jutaan</small>

					<div class="widget-body text-center">

						
						<!-- this is what the user will see -->
						<small align="center">Merah: 2023</small>
						<small class="text-center">Hijau: 2024</small>
						<canvas id="chart" height="200"></canvas>						

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>

		<article class="col-sm-6">
			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
					<span class="widget-icon"> <i class="glyphicon glyphicon-list-alt txt-color-darken"></i> </span>
					<h2>Kondisi Penerimaan Per Jenis Retribusi (non karcis)</h2>
				</header>

				<!-- widget div-->
				<div class="no-padding" style="max-height:390px!important;overflow:auto;">
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						
					</div>
					<!-- end widget edit box -->

					<div class="widget-body">
						<?php
						echo "
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th>Jenis Retribusi</th>
									<th>s.d ".get_monthName($prev_month).($prev_month==12?"<br />".$prev_year:"")." (Rp.)</th>
									<th>".get_monthName($curr_month)." (Rp.)</th>
									<th>Perkembangan</th>
								</tr>";
								$tot_perkembangan = ($totPrevMonth>1?$totCurrMonth/$totPrevMonth*100:0);

								echo "<tr>
									<td width='50%' align='right'><b>Total</b></td>
									<td align='right'><b>".number_format($totPrevMonth)."</b></td>
									<td align='right'><b>".number_format($totCurrMonth)."</b></td>
									<td align='right'><b>".number_format($tot_perkembangan,2,'.',',')."%</b></td>
								</tr>								
							</thead>
							<tbody>";
								$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' ORDER BY id_jenis_retribusi ASC LIMIT 3";
								$result = $db->Execute($sql);

								if(!$result)
									echo $db->ErrorMsg();

								while($row1 = $result->FetchRow())
								{									
									$sql1 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
											WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$prev_year."))
											AND (kd_rekening LIKE '".$row1['kd_rekening']."%')";

									$sql2 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
											WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))
											AND (kd_rekening LIKE '".$row1['kd_rekening']."%')";
									
									$tot_bln1 = $db->getOne($sql1);
									$tot_bln1 = (is_null($tot_bln1)?0:$tot_bln1);
									$tot_bln2 = $db->getOne($sql2);
									$tot_bln2 = (is_null($tot_bln2)?0:$tot_bln2);
									$perkembangan = ($tot_bln1>1?$tot_bln2/$tot_bln1*100:0);

									echo "<tr class='success'>
									<td><b>".$row1['jenis_retribusi']."</b></td>
									<td align='right'><b>".number_format($tot_bln1)."</b></td>
									<td align='right'><b>".number_format($tot_bln2)."</b></td>
									<td align='right'><b>".number_format($perkembangan,2,'.',',')."</b></td>
									</tr>";

									$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '".$row1['kd_rekening']."%' AND length(kd_rekening)>5 AND non_karcis='1' ORDER BY id_jenis_retribusi ASC";
									$result2 = $db->Execute($sql);
									
									while($row2 = $result2->FetchRow())
									{
										/*
										$sql1 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
											WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$prev_year."))
											AND (kd_rekening='".$row2['kd_rekening']."')";

										$sql2 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
												WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))
												AND (kd_rekening='".$row2['kd_rekening']."')";
										*/
										$sql1 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
											WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$prev_year."))
											AND (a.kd_rekening LIKE '".$row1['kd_rekening']."%') AND (a.nm_rekening = '".$row2['jenis_retribusi']."') 
											";

										$sql2 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
												WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))
												AND (a.kd_rekening LIKE '".$row1['kd_rekening']."%') AND (a.nm_rekening = '".$row2['jenis_retribusi']."')";

										$tot_bln1 = $db->getOne($sql1);
										$tot_bln1 = (is_null($tot_bln1)?0:$tot_bln1);
										$tot_bln2 = $db->getOne($sql2);
										$tot_bln2 = (is_null($tot_bln2)?0:$tot_bln2);
										$perkembangan = ($tot_bln1>1?$tot_bln2/$tot_bln1*100:0);

										echo "<tr>
										<td>".$row2['jenis_retribusi']."</td>
										<td align='right'>".number_format($tot_bln1)."</td>
										<td align='right'>".number_format($tot_bln2)."</td>
										<td align='right'>".number_format($perkembangan,2,'.',',')."</td>
										</tr>";
									}
								}
							echo "</tbody>
							
						</table>";
						?>
						<!-- end content -->
					</div>

				</div>				
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>

		<article class="col-sm-6">
			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
					<span class="widget-icon"> <i class="glyphicon glyphicon-list-alt txt-color-darken"></i> </span>
					<h2>Kondisi Penerimaan Per Jenis Retribusi (karcis)</h2>
				</header>

				<!-- widget div-->
				<div class="no-padding" style="max-height:390px!important;overflow:auto;">
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						
					</div>
					<!-- end widget edit box -->

					<div class="widget-body">
						<?php
						echo "
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th>Jenis Retribusi</th>
									<th>s.d ".get_monthName($prev_month).($prev_month==12?"<br />".$prev_year:"")." (Rp.)</th>
									<th>".get_monthName($curr_month)." (Rp.)</th>
									<th>Perkembangan</th>
								</tr>";
								$tot_perkembangan = ($totPrevMonth2>1?$totCurrMonth2/$totPrevMonth2*100:0);

								echo "<tr>
									<td width='50%' align='right'><b>Total</b></td>
									<td align='right'><b>".number_format($totPrevMonth2)."</b></td>
									<td align='right'><b>".number_format($totCurrMonth2)."</b></td>
									<td align='right'><b>".number_format($tot_perkembangan,2,'.',',')."%</b></td>
								</tr>								
							</thead>
							<tbody>";
								$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' ORDER BY id_jenis_retribusi ASC LIMIT 3";
								$result = $db->Execute($sql);

								if(!$result)
									echo $db->ErrorMsg();

								while($row1 = $result->FetchRow())
								{									
									$sql1 = "SELECT SUM(a.total_retribusi) as total_bayar FROM app_pengembalian_karcis as a 
											LEFT JOIN app_permohonan_karcis AS b on a.fk_permohonan=b.id_permohonan											
											WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)='$prev_month') AND (EXTRACT(YEAR FROM a.tgl_pengembalian)='$prev_year'))
											AND (b.kd_rekening LIKE '".$row1['kd_rekening']."%')";

									$sql2 = "SELECT SUM(a.total_retribusi) as total_bayar FROM app_pengembalian_karcis as a 
											LEFT JOIN app_permohonan_karcis AS b on a.fk_permohonan=b.id_permohonan											
											WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)='$curr_month') AND (EXTRACT(YEAR FROM a.tgl_pengembalian)='$curr_year'))
											AND (b.kd_rekening LIKE '".$row1['kd_rekening']."%')";
									
									$tot_bln1 = $db->getOne($sql1);
									$tot_bln1 = (is_null($tot_bln1)?0:$tot_bln1);
									$tot_bln2 = $db->getOne($sql2);
									$tot_bln2 = (is_null($tot_bln2)?0:$tot_bln2);
									$perkembangan = ($tot_bln1>1?$tot_bln2/$tot_bln1*100:0);

									echo "<tr class='success'>
									<td><b>".$row1['jenis_retribusi']."</b></td>
									<td align='right'><b>".number_format($tot_bln1)."</b></td>
									<td align='right'><b>".number_format($tot_bln2)."</b></td>
									<td align='right'><b>".number_format($perkembangan,2,'.',',')."</b></td>
									</tr>";

									$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '".$row1['kd_rekening']."%' AND length(kd_rekening)>5 AND karcis='1' ORDER BY id_jenis_retribusi ASC";
									$result2 = $db->Execute($sql);
									
									while($row2 = $result2->FetchRow())
									{
										/*
										$sql1 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
											WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$prev_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$prev_year."))
											AND (kd_rekening='".$row2['kd_rekening']."')";

										$sql2 = "SELECT SUM(a.total_bayar) FROM app_pembayaran_retribusi as a 											
												WHERE((EXTRACT(MONTH FROM a.tgl_pembayaran)=".$curr_month.") AND (EXTRACT(YEAR FROM a.tgl_pembayaran)=".$curr_year."))
												AND (kd_rekening='".$row2['kd_rekening']."')";
										*/
										$sql1 = "SELECT SUM(a.total_retribusi) as total_bayar FROM app_pengembalian_karcis as a 
											LEFT JOIN app_permohonan_karcis AS b on a.fk_permohonan=b.id_permohonan											
											WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)='$prev_month') AND (EXTRACT(YEAR FROM a.tgl_pengembalian)='$prev_year'))
											AND (b.kd_rekening = '".$row2['kd_rekening']."')";

										$sql2 = "SELECT SUM(a.total_retribusi) as total_bayar FROM app_pengembalian_karcis as a 
											LEFT JOIN app_permohonan_karcis AS b on a.fk_permohonan=b.id_permohonan											
											WHERE((EXTRACT(MONTH FROM a.tgl_pengembalian)='$curr_month') AND (EXTRACT(YEAR FROM a.tgl_pengembalian)='$curr_year'))
											AND (b.kd_rekening = '".$row2['kd_rekening']."')";

										$tot_bln1 = $db->getOne($sql1);
										$tot_bln1 = (is_null($tot_bln1)?0:$tot_bln1);
										$tot_bln2 = $db->getOne($sql2);
										$tot_bln2 = (is_null($tot_bln2)?0:$tot_bln2);
										$perkembangan = ($tot_bln1>1?$tot_bln2/$tot_bln1*100:0);

										echo "<tr>
										<td>".$row2['jenis_retribusi']."</td>
										<td align='right'>".number_format($tot_bln1)."</td>
										<td align='right'>".number_format($tot_bln2)."</td>
										<td align='right'>".number_format($perkembangan,2,'.',',')."</td>
										</tr>";
									}
								}
							echo "</tbody>
							
						</table>";
						?>
						<!-- end content -->
					</div>

				</div>				
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>
	</div>

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
	
	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 * TO LOAD A SCRIPT:
	 * var pagefunction = function (){ 
	 *  loadScript(".../plugin.js", run_after_loaded);	
	 * }
	 * 
	 * OR you can load chain scripts by doing
	 * 
	 * loadScript(".../plugin.js", function(){
	 * 	 loadScript("../plugin.js", function(){
	 * 	   ...
	 *   })
	 * });
	 */
	
	var  myNewChart_1, myNewChart_2;

	// pagefunction

	var pagefunction = function() {
		// clears the variable if left blank

		    // BAR CHART

		    var barOptions = {
			    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
			    scaleBeginAtZero : true,
			    //Boolean - Whether grid lines are shown across the chart
			    scaleShowGridLines : true,
			    //String - Colour of the grid lines
			    scaleGridLineColor : "rgba(0,0,0,.05)",
			    //Number - Width of the grid lines
			    scaleGridLineWidth : 1,
			    //Boolean - If there is a stroke on each bar
			    barShowStroke : true,
			    //Number - Pixel width of the bar stroke
			    barStrokeWidth : 1,
			    //Number - Spacing between each of the X value sets
			    barValueSpacing : 5,
			    //Number - Spacing between data sets within X values
			    barDatasetSpacing : 1,
			    //Boolean - Re-draw chart on page resize
		        responsive: true,
			    //String - A legend template
			    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
		    }

		    // var barData = {
		    //     labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul","Agt","Sep","Okt","Nov","Des"],
		    //      datasets: [
			//         {
			//             label: "My First dataset",
			//             fillColor: "rgba(60,255,0,0.5)",
			//             strokeColor: "rgba(9,218,18,0.8)",
			//             highlightFill: "rgba(245,254,5,0.75)",
			//             highlightStroke: "rgba(230,238,0,1)",

			//         }
			//     ]
		    // };

			var barData2 = {
		        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul","Agt","Sep","Okt","Nov","Des"],
		         datasets: [
			        {
			            label: "2023",
			            fillColor: "rgba(250,0,0,0.8)",
			            data: <?php echo $lineData_last; ?>

			        },
					{
			            label: "2024",
			            fillColor: "rgba(0,250,21,0.8)",
			            data: <?php echo $lineData_current; ?>

			        }
			    ]
		    };

		    // render chart
			var ctx = document.getElementById("chart").getContext("2d");
			myNewChart_1 = new Chart(ctx).Line(barData2, barOptions);

		    // END BAR CHART

		    

	};
	
	loadScript("js/plugin/chartjs/chart.min.js", pagefunction); 

	// end pagefunction

	// destroy generated instances 
	// pagedestroy is called automatically before loading a new page
	// only usable in AJAX version!

	var pagedestroy = function(){
		
		//destroy all charts
    	myNewChart_1.destroy();
		myNewChart_1=null;

    	myNewChart_2.destroy();
    	myNewChart_2=null;

    	myNewChart_3.destroy();
    	myNewChart_3=null;

    	myNewChart_4.destroy();
    	myNewChart_4=null;

    	myNewChart_5.destroy();
    	myNewChart_5=null;

    	myNewChart_6.destroy();
    	myNewChart_6=null;

    	if (debugState){
			root.console.log("✔ Chart.js charts destroyed");
		} 
	}

	// end destroy
	
</script>
