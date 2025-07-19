<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_skrd = $_GET['id'];

  $sql = "SELECT 
          a.npwrd,a.wp_wr_nama,a.wp_wr_alamat,a.nm_rekening,
          a.kd_rekening,a.tipe_retribusi,a.no_skrd,a.bln_retribusi,
          a.thn_retribusi, a.tgl_penetapan,
          b.keterangan,b.dasar_pengenaan,b.imb,c.korek_denda,c.jenis_denda
          FROM app_skrd as a           
          LEFT JOIN app_nota_perhitungan as b ON (a.id_skrd=b.fk_skrd) 
          LEFT JOIN 
            (SELECT x.kd_rekening,y.korek_denda,y.jenis_denda
               FROM app_ref_jenis_retribusi as x 
               LEFT JOIN 
                (SELECT id_jenis_retribusi,kd_rekening as korek_denda,jenis_retribusi as jenis_denda 
                   FROM app_ref_jenis_retribusi) as y 
                 ON (x.fk_denda=y.id_jenis_retribusi)) as c 
               ON (a.kd_rekening=c.kd_rekening) WHERE(a.id_skrd='".$id_skrd."')";

	$result = $db->Execute($sql);
  $n_skrd = $result->RecordCount();

  if($n_skrd>0)
  {
    $row1 = $result->FetchRow();   

    if($row1['tipe_retribusi']=='1')
    {

      $gr_sub_select = "SELECT total_nilai_imb FROM app_rincian_nota_perhitungan_imb2 as x WHERE(x.fk_nota=a.id_nota)";

      if($row1['imb']=='1'){
        $x = explode(' ',$row1['dasar_pengenaan']);
        $thn_dasar_pengenaan = end($x);
        if($thn_dasar_pengenaan=='2017')
        {
          $gr_sub_select = "SELECT grand_total_retribusi FROM app_perhitungan_imb2017 as x WHERE(x.fk_nota=a.id_nota)";
        }
      }

      $sql = "SELECT imb,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(ketetapan) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE (".$gr_sub_select.")
               END) as ketetapan_retribusi,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(total) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE (".$gr_sub_select.")
               END) as total_retribusi,
               (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(kenaikan) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_kenaikan,
               (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(bunga) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_bunga,
              (CASE WHEN a.imb='0' THEN 
               (SELECT SUM(denda) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
               ELSE '0'
               END) as total_denda
               FROM app_nota_perhitungan as a WHERE(a.fk_skrd='".$id_skrd."') ;";
            

      $row2 = $db->getRow($sql);
      $ketetapan_retribusi = $row2['ketetapan_retribusi'];
      $total_kenaikan = $row2['total_kenaikan'];
      $total_bunga = $row2['total_bunga'];
      $total_denda = $row2['total_denda'];
      $total_retribusi = $row2['total_retribusi'];
      
    }
    else
    {
      $sql = "SELECT nilai_total_perforasi,total_retribusi FROM app_permohonan_karcis WHERE(fk_skrd='".$id_skrd."')";
      $row2 = $db->getRow($sql);
      $ketetapan_retribusi = $row2['nilai_total_perforasi'];
      $total_retribusi = $row2['total_retribusi'];
      $total_kenaikan = 0;
      $total_bunga = 0;
      $total_denda = 0;
    }
    
    $system_params = $global->get_system_params();
  }

?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Nota Perhitungan Retribusi Daerah</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
  	</head>
  	<body>
      <?php
        if($n_skrd>0)
        {
      ?>
  		<div style="padding:10px;">
    		<table style="border:1px solid #000" cellpaddding=0 cellspacing=0 width="100%">
    			<tr>
    				<td width="40%" style="border-right:1px solid #000;border-bottom:1px solid #000;">
    					<table border=0 width="100%">
    						<tr>
    							<td width="20%"><img src="../../img/logo_pemkot_bekasi.png" width="72"/></td>
    							<td valign="top">
    								<h4>PEMERINTAH <?=strtoupper($system_params[7]." ".$system_params[6]);?><br />
                      <?=strtoupper($system_params[2])?>
                    </h4>
                    <small><?=$system_params[3];?><br />
                      <?php echo "Telp. ".$system_params[4].", Fax. ".$system_params[4]; ?>
                    </small>
                    <h4 style="margin-top:2px!important;"><?=strtoupper($system_params[6]);?></h4>
    							</td>
    						</tr>
    					</table>
    				</td>
    				<td align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;" valign="top">
    					<h4 style="margin:0">SKRD<br />
    						(SURAT KETETAPAN RETRIBUSI DAERAH)
    					</h4>
    					<table border=0 style="100%" cellpadding=0 cellspacing=0>
    						<tr>
    							<td width="15%">&nbsp;</td>
    							<td>Masa</td><td>&nbsp;&nbsp;: <?php echo get_monthName($row1['bln_retribusi']);?></td></tr>
    						</tr>
    						<tr>
    							<td width="15%">&nbsp;</td>
    							<td>Tahun</td><td>&nbsp;&nbsp;: <?php echo $row1['thn_retribusi'];?></td></tr>
    						</tr>
    					</table>
    				</td>
    				<td align="center" valign="top" style="border-bottom:1px solid #000;">
    					No. Urut SKRD<br />
    					<h4 style="margin-top:10px!important;font-size:1.6em">
    						<?php echo sprintf('%02d',$row1['no_skrd']);?>
    					</h4>
    				</td>
    			</tr>
    			<tr>
    				<td colspan="3" style="border-bottom:1px solid #000;">
    					<table width="100%">
    						<tr>
    							<td width="8%">Nama</td><td>: <?php echo $row1['wp_wr_nama'];?></td>
    						</tr>
    						<tr>
    							<td>Alamat</td><td>: <?php echo $row1['wp_wr_alamat'];?></td>
    						</tr>
    					</table>
    				</td>
    			</tr>
    			<tr>
    				<td colspan="3" align="center">
    					DASAR HUKUM PENGENAAN RETRIBUSI<br />
    					<?php echo $row1['dasar_pengenaan'];?>
    				</td>
    			</tr>
    		</table>

    		<table class="report" cellpadding=0 cellspacing=0>
    			<thead>
    				<tr>
    					<th width="4%">NO</th>
    					<th>KODE REKENING</th>
    					<th colspan="3" width="60%">JENIS PAJAK RETRIBUSI</th>
    					<th>JUMLAH</th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php
            
    				echo "
    				<tr>
    					<td align='center'>1</td>
    					<td align='center'>".$row1['kd_rekening']."</td>
    					<td colspan='3'>".$row1['nm_rekening']."<br />".$row1['keterangan']."</td>
    					<td align='right'>".number_format($total_retribusi,0,',','.')."</td>
    				</tr>";

            if($total_denda>0){
              echo "
              <tr>
                <td align='center' style='border-top:none!important;'>2</td>
                <td align='center' style='border-top:none!important;'>".$row1['korek_denda']."</td>
                <td colspan='3' style='border-top:none!important;'>".$row1['jenis_denda']."</td>
                <td align='right' style='border-top:none!important;'>".number_format($total_denda,0,',','.')."</td>
              </tr>";
            }

    				echo "<tr>
    					<td colspan='2'></td>  					
    					<td colspan='3'>Jenis Ketetapan Pokok Retribusi</td>
    					<td></td>
    				</tr>
            <tr>
              <td colspan='2' style='border-top:none;'></td>            
              <td width='10%'>Jumlah Sanksi :</td>
              <td width='2%' style='border-left:none!important'>a.</td><td style='border-left:none!important'>Bunga</td>
              <td align='right'>".number_format($total_bunga,0,',','.')."</td>
            </tr>
    				<tr>
    					<td colspan='2' style='border-top:none;'></td>  					
    					<td></td>
              <td style='border-left:none!important'>b.</td><td style='border-left:none!important'>Kenaikan</td>
              <td align='right'>".number_format($total_kenaikan,0,',','.')."</td>
    				</tr>
            
    				<tr>
    					<td colspan='2' style='border-top:none;border-bottom:none'></td>
    					<td colspan='3'>Jumlah Keseluruhan</td>
    					<td align='right'><b>".number_format($total_retribusi,0,',','.')."</td>
    				</tr>
    				<tr>
    				<td colspan='6'>
  	  				<table width='100%' style='margin:5px;'>
  	  				<tr>
  	  					<td width='5%' style='border:none;'>&nbsp;</td>
  	  					<td width='10%' style='border:none;'>Dengan Huruf</td>
  	  					<td style='border:1px solid #000;border-right:none;'><b>".ucwords(NumToWords($total_retribusi))." Rupiah ----</b></td>
  	  				</tr>
  	  				</table>
    				</td>  				  				
    				</tr>
    				<tr>
    				<td colspan='6'>
    					<div style='margin:10px;'>
    					<h4><u>PERHATIAN</u></h4>
    					<ol type='1' style='margin:0;padding:0;padding-left:15px;'>
    						<li>Harap penyetoran dilakukan melalui Kas Daerah pada Bank Jabar Banten Kas Pemerintah Kota Bekasi.</li>
    						<li>SKRD ini berfungsi juga sebagai Nota Hitung dan Surat Pemberitahuan Retribusi Daerah (SKRD).</li>
    					</ol>
    					</div>
    				</td>
    				</tr>
    				<tr>
    				<td colspan='6'>
    					<table border=0 width='100%'>
    					<tr>
    						<td width='60%' style='border:none'>&nbsp;</td>
    						<td align='center' style='border:none'>
    						Bekasi, ".indo_date_format($row1['tgl_penetapan'],'longDate')."<br />
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
    					</tr>
    					</table>

    				</td>
    				</tr>";
    				?>
    			</tbody>
    		</table>
  		</div>
      <?php
        }
        else
        {
          echo "<center><font color='red'>Data tidak ditemukan!</font></center>";
        }
      ?>
 	</body>
</html>