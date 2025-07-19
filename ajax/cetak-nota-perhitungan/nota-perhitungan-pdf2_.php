<?php
	
	require_once("../../vendor/autoload.php");
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
  	require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

	$mpdf = new \Mpdf\Mpdf(['tempDir' => 'C:\inetpub\wwwroot\siprd\vendor\mpdf\mpdf\tmp',
							'mode'=>'utf-8',
							'format'=>'Folio-L',
							'orientation'=>'L']);

  	$global = new global_obj($db);

	$id_nota = $_GET['id'];

	$sql = "SELECT a.*,b.wp_wr_nama,b.wp_wr_alamat,b.wp_wr_lurah,b.wp_wr_camat,b.tgl_penetapan,a.total_retribusi
			FROM app_nota_perhitungan as a 
      		LEFT JOIN app_skrd as b ON (a.fk_skrd=b.id_skrd)
			WHERE (a.id_nota='".$id_nota."')";  

	$result = $db->Execute($sql);

  if(!$result){
    die("<center><font color='red'>terjadi kesalahan</font></center>");
  }

	$row1 = $result->FetchRow();	

  if(!is_null($row1['id_nota']) and !empty($row1['id_nota'])){
  	$dasar_pengenaan = $row1['dasar_pengenaan'];
  	$x = explode(' ',$dasar_pengenaan);
  	$thn_dasar_pengenaan = end($x);

  	if($thn_dasar_pengenaan=='2012'){
    	$sql = "SELECT * FROM app_rincian_nota_perhitungan_imb2 WHERE fk_nota='".$id_nota."'";
  	}else{    
    	$sql = "SELECT * FROM app_perhitungan_imb2017 as a LEFT JOIN app_indeks_terintegrasi_imb2017 as b ON (a.fk_nota=b.fk_nota)
        	    WHERE a.fk_nota='".$id_nota."'";
  	}

	 $result = $db->Execute($sql);

	 if(!$result)
	    die($db->ErrorMsg());

	 $row2 = $result->FetchRow();
	  
	 $grand_total_retribusi = ($thn_dasar_pengenaan=='2012'?$row2['total_nilai_imb']:$row2['grand_total_retribusi']);
	 $system_params = $global->get_system_params();  	

	 $html = "<!DOCTYPE html><html>
		  	<head>
		    	<meta charset='UTF-8'>
		    	<title>".$_SITE_TITLE." - Nota Perhitungan Retribusi Daerah</title>
		    	<link rel='stylesheet' type='text/css' href='../../css/report-style.css'/>
		      <style type='text/css'>
		        table.no_border{width:100%;}
		        table.no_border td{border:none!important};
		      </style>
		  	</head>
		  	<body>          
  			<div style='padding:10px;'>";

        if($thn_dasar_pengenaan=='2012'){
          	$sql = "SELECT * FROM app_rincian_nota_perhitungan_imb1 WHERE(fk_nota='".$id_nota."')";
          	$result = $db->Execute($sql);

          	if(!$result)
            	die($db->ErrorMsg());

          	$arr_data1 = array();
          	$i=0;
          	while($row3 = $result->FetchRow())
          	{
            	foreach($row3 as $key => $val){
                	$key=strtolower($key);        
                	$arr_data1[$i][$key] = $val;
            	}
            	$i++;
          	}

        	$html .= "<table style='border:1px solid #000' cellpaddding=0 cellspacing=0 width='100%'>
      				  <tr><td width='35%' style='border-right:1px solid #000;border-bottom:1px solid #000;'>
      				  <table border=0 width='100%'>
      				  <tr><td width='30%'><img src='../../img/logo_pemkot_bekasi.jpg' width='100'/></td>
      				  <td valign='top'>
      					<h4 style='margin-top:0;margin-bottom:5px'>PEMERINTAH ".strtoupper($system_params[7]." ".$system_params[6])."<br />
                      	".strtoupper($system_params[2])."</h4>
                      <small>".$system_params[3].' '.$system_params[7].' '.$system_params[6]."<br />
                       Telp. ".$system_params[4].", Fax. ".$system_params[4]."
                      </small>
                      <h4 style='margin:0;'>".strtoupper($system_params[6])."</h4></td></tr>
      				</table>
      				</td>
      				<td align='center' style='border-right:1px solid #000;border-bottom:1px solid #000;' valign='top'>
      				<h4 style='margin:0'>SKRD<br />NOTA PERRHITUNGAN RETRIBUSI IMB<br />  						
      				</h4><br />
      				<table border=0 style='100%' cellpadding=0 cellspacing=0 width='90%'>
					<tr><td align='left'>BULAN/TAHUN</td>
      				<td align='left'>: ".get_monthName($row1['bln_retribusi'])." ".$row1['thn_retribusi']."</td>
					</tr>
					<tr>
                    <td align='left'>DASAR PENGENAAN</td>
                    <td align='left'>: ".$row1['dasar_pengenaan']."</td>
                  </tr>
                  </table>
                  </td>
      				<td align='center' valign='top' style='border-bottom:1px solid #000;'>
      					<table class='no_border'>
      						<tr>
                    <td align='left'>Nomor Nota Perhitungan</td>
                    <td align='left'> : ".sprintf('%02d',$row1['no_nota_perhitungan'])."</td>
                  </tr>
      						<tr>
                    <td align='left'>No. Kohir/Urut</td><td align='left'> : ....................</td>
                  </tr>
      						<tr><td align='left'>NPWD</td>
      						<td align='left'> : ".$row1['npwrd']."</td></tr>
      					</table>
      				</td>
      			</tr>
      			<tr>
      				<td colspan='3'>
      					<table width='100%'>
      						<tr>
      							<td width='8%'>Nama</td><td>: ".$row1['wp_wr_nama']."</td>
      						</tr>
      						<tr>
      							<td>Lokasi</td><td>: Kel. ".$row1['wp_wr_lurah'].", Kec.".$row1['wp_wr_camat']."</td>
      						</tr>
      					</table>
      				</td>
      			</tr>
      		</table>
          <table class='report' cellpadding=0 cellspacing=0>
            <thead>
      				<tr>
      					<th>&nbsp;</th>
      					<th>BANGUNAN</th>
      					<th>TIPE</th>
      					<th>LUAS<br />(M<sup>2</sup>)</th>
      					<th>(M<sup>2</sup>)<br/>(RP.)</td>
      					<th>BIAYA BANGUNAN<br />(RP.)</td>
      					<th>KOEFISIEN<br/>KJ.GB.LB.TB</th>
      					<th>NILAI BANGUNAN<br />(RP.)</td>
                <th>PROSENTASE BIAYA<br />(%)</td>
                <th class='rborder'>BIAYA IMB<br />(RP.)</td>
      				</tr>
      			</thead>
      			<tbody>
              <tr>
                <td></td>
                <td valign='top'><b>".$row1['jenis_bangunan']."</b><br /><br />
                <table class='no_border'>";
                  foreach($arr_data1 as $val)
                  {
                    	$html .= "<tr><td>".$val['bangunan']."</tr>";
                  }
                $html .= "</table></td>
                <td align='center' valign='top'><b>".$row1['tipe_bangunan']."</b></td>
                <td valign='top'>
                &nbsp;<br /><br />
                <table class='no_border'>";
                  foreach($arr_data1 as $val)
                  {
                    $html .= "<tr><td align='right'>".number_format($val['luas'],2,',','.')."</tr>";
                  }
                $html .= "</table>
                </td> 
                <td valign='top'>
                &nbsp;<br /><br />
                <table class='no_border'>";
                  foreach($arr_data1 as $val)
                  {
                    $html .= "<tr><td align='right'>".number_format($val['nilai_satuan'],0,',','.')."</tr>";
                  }
                $html .= "</table>
                </td> 
                <td valign='top'>
                &nbsp;<br /><br />
                <table class='no_border'>";
                  foreach($arr_data1 as $val)
                  {
                    $html .= "<tr><td align='right'>".number_format($val['biaya_bangunan'],0,',','.')."</tr>";
                  }
                $html .= "</table>
                </td> 
                <td valign='top'>
                &nbsp;<br /><br />
                <table class='no_border'>";
                  foreach($arr_data1 as $val)
                  {
                    $html .= "<tr><td align='center'>
                    ".number_format($val['kj'],2,',','.')." x ".number_format($val['gb'],2,',','.')." x ".number_format($val['lb'],2,',','.')." x ".number_format($val['tb'],2,',','.')."
                    </tr>";
                  }
                $html .= "</table>
                </td> 
                <td valign='top'>
                &nbsp;<br /><br />
                <table class='no_border'>";
                  $total_nilai_bangunan = 0;
                  foreach($arr_data1 as $val)
                  {
                    $total_nilai_bangunan += $val['nilai_bangunan'];
                    $html .= "<tr><td align='right'>".number_format($val['nilai_bangunan'],0,',','.')."</tr>";
                  }
                $html .= "</table>
                </td> 
                <td>
                  <table class='no_border'>";
                    $koef_types = array('permohonan'=>'Permohonan','penatausahaan'=>'Penatausahaan','plat_nomor'=>'Plat Nomor',
                                        'penerbitan_srtif_imb'=>'Penerbitan SRTIF IMB','verifikasi_data_tkns'=>'Verifikasi Data TKNS','pengukuran'=>'Pengukuran',
                                        'pematokan_gsj_gss'=>'Pematokan GSJ GSS','gbr_rencana'=>'Gambar Rencana','pengawasan_izin'=>'Pengawasan Izin');                

                    foreach($koef_types as $key=>$val)
                    {
                      $html .= "
                      <tr>
                        <td>Koef. ".ucfirst($val)."</td><td> : ".number_format($row2['koef_'.$key],2,',','.')."</td>
                      </tr>";
                    }
                  $html .= "</table>
                </td>
                <td class='rborder'>
                  <table class='no_border'>";
                    foreach($koef_types as $key=>$val)
                    {
                      $html .= "
                      <tr>
                        <td align='right'>".number_format($row2['nilai_'.$key],0,',','.')."</td>
                      </tr>";
                    }
                  $html .= "</table>
                </td>
              </tr>
              <tr>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td align='right' class='bborder'>
                  <b>".number_format($total_nilai_bangunan,0,',','.')."&nbsp;</b>
                </td>
                <td style='border-top:none!important;' class='bborder'></td>
                <td style='border-top:none!important;' class='rborder bborder'></td>
              </tr>
              <tr>
                <td colspan='7' style='border-bottom:none!important'>
                </td>
                <td colspan='2' align='center'>
                <h3>JUMLAH RETRIBUSI</h3>
                </td>
                <td align='right' class='rborder'>
                  <b>".number_format($row2['total_nilai_imb'],0,',','.')."</b>
                </td>
              </tr>
              <tr>
              <td colspan='3' class='bborder'></td>
              <td colspan='7' class='bborder rborder'>Jumlah dengan huruf : <b>".ucwords(NumToWords($row1['total_retribusi']))." Rupiah ----</b></td>
              </tr>
      			</tbody>
      		</table>";
          
        }else{

          $arr_data1 = array();
          $arr_data2 = array();

          $sql = "SELECT * FROM app_rincian_bangunan_imb2017 WHERE(fk_nota='".$id_nota."')";
          $result = $db->Execute($sql);

          if(!$result)
            die($db->ErrorMsg());          

          $i=0;
          while($_row = $result->FetchRow())
          {
            foreach($_row as $key => $val){
                $key=strtolower($key);        
                $arr_data1[$i][$key] = $val;
            }
            $i++;
          }


          $sql = "SELECT * FROM app_rincian_prasarana_imb2017 WHERE(fk_nota='".$id_nota."')";
          $result = $db->Execute($sql);

          if(!$result)
            die($db->ErrorMsg());          

          $i=0;
          while($_row = $result->FetchRow())
          {
            foreach($_row as $key => $val){
                $key=strtolower($key);        
                $arr_data2[$i][$key] = $val;
            }
            $i++;
          }

        $html .= "<table style='border:1px solid #000;border-bottom:none' cellpaddding=0 cellspacing=0 width='100%'>
            <tr>
              <td width='35%' style='border-right:1px solid #000;border-bottom:1px solid #000;'>
                <table border=0 width='100%'>
                  <tr>
                    <td valign='top' align='center'>
                      <h4 style='margin-top:0;margin-bottom:5px'>PEMERINTAH ".strtoupper($system_params[7]." ".$system_params[6])."<br />
                      ".strtoupper($system_params[2])."
                      </h4>
                      <small>".$system_params[3].' '.$system_params[7].' '.$system_params[6]."<br />
                        Telp. ".$system_params[4].", Fax. ".$system_params[4]."
                      </small>
                      <h4 style='margin:0;'>".strtoupper($system_params[6])."</h4>
                    </td>
                  </tr>
                </table>
              </td>
              <td align='center' style='border-right:1px solid #000;border-bottom:1px solid #000;' valign='top'>
                <h4 style='margin:0'>
                  NOTA PERRHITUNGAN RETRIBUSI IMB<br />             
                </h4><br />
                <table border=0 style='100%' cellpadding=0 cellspacing=0 width='90%'>
                  <tr>
                    <td align='left'>Bulan</td>
                    <td align='left'>: ".get_monthName($row1['bln_retribusi'])."</td>
                  </tr>
                  <tr>
                    <td align='left'>Tahun</td>
                    <td align='left'>: ".get_monthName($row1['thn_retribusi'])."</td>
                  </tr>
                  <tr>
                    <td align='left'>Pengenaan</td>
                    <td align='left'>: ".$row1['dasar_pengenaan']."</td>
                  </tr>
                </table>
              </td>
              <td align='center' valign='top' style='border-bottom:1px solid #000;'>
                <table class='no_border'>
                  <tr><td align='left'>Nomor Nota</td>
                  <td align='left'> : ".sprintf('%02d',$row1['no_nota_perhitungan'])."</td></tr>
                  <tr><td align='left'>No. Kohir/Urut</td><td align='left'> : ....................</td></tr>                  
                </table>
              </td>
            </tr>
            <tr>
              <td style='border-right:1px solid #000;'>
                Nama : <b>".$row1['wp_wr_nama']."</b>
              </td>
              <td colspan='2'>
                Lokasi : Kel. <b>".$row1['wp_wr_lurah']."</b>, Kec. <b>".$row1['wp_wr_camat']."</b>
              </td>
            </tr>
          </table>
          <table class='report2' cellpadding=0 cellspacing=0 style='font-size:0.9em'>
            <thead>
              <tr>
                <th rowspan='2'>NO</th>
                <th rowspan='2' width='20%'>JENIS BANGUNAN</th>
                <th rowspan='2'>LUAS BANGUNAN</th>
                <th rowspan='2'>INDEKS PRASARANA BANGUNAN GEDUNG</th>
                <th colspan='4'>INDEKS KLASIFIKASI BANGUNAN GEDUNG</th>
                <th rowspan='2'>INDEKS PENGGUNAAN GEDUNG</th>
                <th rowspan='2'>INDEKS WAKTU PENGGUNAAN</th>
                <th rowspan='2'>INDEKS BANGUNAN BAWAH PERMUKAAN TANAH</th>
                <th rowspan='2'>HARGA SATUAN RETRIBUSI</th>
                <th rowspan='2' class='rborder'>JUMLAH NILAI RETRIBUSI</th>
              </tr>
              <tr>
                <th width='15%'>PARAMETER</th>
                <th>BOBOT</th>
                <th>INDEKS</th>
                <th style='border-right:none'>NILAI PARAMETER</th>
              </tr>
            </thead>
            <tbody>";

              function integrity_index_field_gen($label){
                  return str_replace(' ','_', strtolower($label));
              }
              $integrity_index_fields = array('Kompleksitas','Permanensi','Resiko Kebakaran','Zonasi Gempa',
                                              'Ketinggian Bangunan','Kepemilikan Bangunan');
              $rows = array();
              $stop = false;
              $i = 0;

              while(!$stop){

                if(isset($integrity_index_fields[$i]) or isset($arr_data1[$i]))
                {
                  if($i==0)
                  {

                    $rows[] = array('',strtoupper($row1['jenis_bangunan']),'','',
                                    $integrity_index_fields[$i],
                                    number_format($row2['bobot_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    number_format($row2['indeks_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    number_format($row2['nilai_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    '','','','',''
                                    );
                  }else if($i==1){
                    $rows[] = array('','','','',$integrity_index_fields[$i],
                                    number_format($row2['bobot_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    number_format($row2['indeks_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    number_format($row2['nilai_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',','),
                                    '','','','',''
                                  );
                  }else{
                    
                    $col2 = (isset($arr_data1[$i-2])?$arr_data1[$i-2]['bangunan']:'');
                    $col3 = (isset($arr_data1[$i-2])?number_format($arr_data1[$i-2]['luas'],2,'.',',').' m<sup>2</sup>':'');

                    $col5 = '';
                    $col6 = '';
                    $col7 = '';
                    $col8 = '';

                    if(isset($integrity_index_fields[$i])){
                      $col5 = $integrity_index_fields[$i];
                      $col6 = number_format($row2['bobot_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',',');
                      $col7 = number_format($row2['indeks_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',',');
                      $col8 = number_format($row2['nilai_'.integrity_index_field_gen($integrity_index_fields[$i])],2,'.',',');
                    }

                    $rows[] = array('',$col2,$col3,'',$col5,$col6,$col7,$col8,'','','','','');

                  }
                }else{
                  $stop = true;
                }

                $i++;
              }

              $html .= "<tr>";
              for($i=0;$i<=12;$i++) 
              {
              		$html .= "<td ".($i==12?"class='rborder'":"").">&nbsp;</td>";
              }
              $html .= "</tr>";

              foreach($rows as $row){
                $html .= "<tr>";
                  
                  for($i=0;$i<=12;$i++){

                    $align = 'left';
                    if($i==2 or $i==5 or $i==6 or $i==7)
                      $align = 'right';

                    $html .= "<td align='".$align."' ".($i==12?"class='rborder'":"").">".$row[$i]."</td>";
                  }

                $html .= "</tr>";
              }

              $html .= "<tr>
              <td align='center'><b>I</b></td>
              <td><b>LUAS SELURUH BANGUNAN</b></td>
              <td align='right'><b>".number_format($row2['total_luas_bangunan'],2,'.',',')." m<sup>2</sup></b></td>
              <td align='right'><b>".number_format($row2['indeks_prasarana'],2,'.',',')."</b></td>
              <td></td><td></td><td></td>
              <td align='right' style='border-top:1px solid #000;'><b>".number_format($row2['total_nilai_indeks_terintegrasi'],2,'.',',')."</b></td>
              <td align='right'><b>".number_format($row2['indeks_penggunaan_gedung'],2,'.',',')."</b></td>
              <td align='right'><b>".number_format($row2['indeks_waktu_penggunaan'],2,'.',',')."</b></td>
              <td align='right'><b>".number_format($row2['indeks_bangunan_bawah_permukaan_tanah'],2,'.',',')."</b></td>
              <td align='right'><b>".number_format($row2['harga_satuan_retribusi_bangunan'])."</b></td>
              <td align='right' class='rborder'><b>".number_format($row2['total_retribusi_bangunan'])."</b></td>
              </tr>";

              $html .= "<tr>";
              for($i=0;$i<=12;$i++) 
              {
              	$html .= "<td ".($i==12?"class='rborder'":"").">&nbsp;</td>";
              }
              $html .= "</tr>";

              $html .= "<tr>
              <td align='center'><b>II</b></td>
              <td><b>PRASARANA BANGUNAN</b></td>";
              for($i=0;$i<=10;$i++) {
              	$html .= "<td ".($i==10?"class='rborder'":"").">&nbsp;</td>";
              }
              $html .= "</tr>";

              foreach($arr_data2 as $row){
                $html .= "<tr>
                  <td></td>
                  <td>".$row['prasarana']."</td>
                  <td align='right'>".$row['luas']." ".$row['satuan']."</td>
                  <td></td><td></td><td></td><td></td><td></td>
                  <td align='right'>".number_format($row['indeks_penggunaan'],2,'.',',')."</td>
                  <td></td><td></td>
                  <td align='right'>".number_format($row['harga_satuan_retribusi'])."</td>
                  <td align='right' class='rborder'>".number_format($row['total_nilai_retribusi'])."</td>
                </tr>";
              }

              $html .= "<tr>";
              for($i=0;$i<=11;$i++) {
              	$html .= "<td>&nbsp;</td>";
              }
              $html .= "<td style='border-top:1px solid #000;' align='right' class='rborder'><b>".number_format($row2['total_retribusi_bangunan'])."</b></td>
              </tr>";

              $html .= "<tr>
              <td align='center'><b>III</b></td>
              <td><b>TOTAL PENATAUSAHAAN</b></td>";
              for($i=0;$i<=9;$i++) $html .= "<td>&nbsp;</td>";
              $html .= "<td align='right' class='rborder'>".number_format($row2['total_penatausahaan'])."</td>
              </tr>
              <tr>
              <td colspan='12' align='center' style='border-top:1px solid #000'><b>TOTAL NILAI RETRIBUSI</b></td>
              <td align='right' style='border-top:1px solid #000' class='rborder'><b>".number_format($row2['grand_total_retribusi'])."</b></td>
              </tr>";

        $html .= "</tbody>
          </table>
          <table style='border:1px solid #000;border-top:none;border-bottom:none!important' cellpaddding=0 cellspacing=0 width='100%'>            
          <tr><td colspan='2'>&nbsp;</td></tr>
          <tr>
            <td align='right' width='30%'>Jumlah dengan huruf :</td>
            <td style='border-bottom:1px solid #000'><b>".ucwords(NumToWords($grand_total_retribusi))." Rupiah ----</b></td>
          </tr>          
        </table>";
        
        }
        
        $html .= "
        <table style='border:1px solid #000;border-top:none' cellpaddding=0 cellspacing=0 width='100%'>            
          <tr><td colspan='3' ".($thn_dasar_pengenaan=='2017'?"class='bborder'":"").">&nbsp;</td></tr>
    		  <tr>
      			<td align='center' ".($thn_dasar_pengenaan=='2017'?"class='tborder'":"").">Mengetahui,<br />
      				a.n Kepala Badan Pendapatan Daerah<br />
      				    Kepala Bidang Pendapatan Daerah<br />
      				<br />
      				<br />
      				<br />
      				<br />
      				<br />
      				<u>".$system_params[11]."</u><br />
              ".$system_params[12]."<br />
              NIP. ".$system_params[13]."
      			</td>
      			<td align='center' ".($thn_dasar_pengenaan=='2017'?"class='tborder'":"").">Diperiksa Oleh,<br />
      				Kepala Subid Retribusi Daerah<br />
      				dan Pendapatan Daerah Lainnya<br />
      				<br />
      				<br />
      				<br />
      				<br />
      				<br />
      				<u>".$system_params[14]."</u><br />
              ".$system_params[15]."<br />
              NIP. ".$system_params[16]."
      			</td>
      			<td ".($thn_dasar_pengenaan=='2017'?"class='tborder'":"").">
      				".$system_params[6].", ".indo_date_format($row1['tgl_penetapan'],'longDate')."<br /><br />
      				<table width='100%' border=0>
      					<tr>
      						<td>Nama</td><td> : ".$system_params[17]."</td>
      					</tr>
      					<tr>
      						<td>Jabatan</td><td> : Pelaksana</td>
      					</tr>
      					<tr>
      						<td colspan='2'><br /></td>
      					</tr>
      					<tr>
      						<td>Tanda Tangan</td><td> : </td>
      					</tr>
      				</table>
      			</td>
          </tr>
          <tr><td colspan='3'>&nbsp;</td></tr>
    		</table>";
    	$html .= "
  		</div>
 	  </body>
    </html>";

  	$mpdf->SetTitle('Nota Perhitungan');
  	$mpdf->WriteHTML($html);
    $numb = sprintf('%04s',$row1['no_nota_perhitungan']);
    $mpdf->Output("nota_perhitungan_".$numb.".pdf","I");
  }else{
    echo "<center><font color='red'>Data tidak ditemukan!</font></center>";
  }

?>