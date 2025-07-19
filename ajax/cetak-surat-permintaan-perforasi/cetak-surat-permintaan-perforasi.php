<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_skrd = $_GET['id'];

  $sql = "SELECT a.no_skrd,a.nm_rekening,b.kd_karcis,b.nilai_per_lembar,b.jumlah_blok,b.isi_per_blok,b.jumlah_lembar,b.tgl_permohonan,b.nm_pemohon,b.nip_pemohon
          FROM app_skrd as a INNER JOIN 
          (SELECT x.fk_skrd,x.kd_karcis,x.nilai_per_lembar,x.jumlah_blok,x.isi_per_blok,x.jumlah_lembar,x.tgl_permohonan,x.nm_pemohon,x.nip_pemohon
           FROM app_permohonan_karcis as x) as b ON(a.id_skrd=b.fk_skrd) WHERE a.id_skrd='".$id_skrd."'";
  
	$result = $db->Execute($sql);
  $n_skrd = $result->RecordCount();

  if($n_skrd>0)
  {
    $row = $result->FetchRow();   
    $system_params = $global->get_system_params();
  }

?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Surat Permintaan Perforasi</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
  	</head>
  	<body>
      <?php
        if($n_skrd>0)
        {
      ?>
  		<div style="padding:10px;">
    		<table style="border:1px solid #000;border-bottom:none;" cellspacing=0 cellpadding=5 width="100%">
    			<tr>
    				<td width="35%" style="border-right:1px solid #000;border-bottom:1px solid #000;">
    					<br />
              <table border=0 width="100%">
    						<tr>    							
    							<td valign="top" align="center">
    								<h3>PEMERINTAH <?=strtoupper($system_params[7]." ".$system_params[6]);?><br />
                      <font style="font-size:1.4em;"><?=strtoupper($system_params[2])?></font>
                    </h3>
                    <?=$system_params[3].", Telp. ".$system_params[4]; ?>
                    <h4><?=strtoupper($system_params[6]);?></h4>
    							</td>
    						</tr>
    					</table>
              <br />
    				</td>
    				<td align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;" valign="top">
    					<br /><br />
              <h3>SURAT PERMINTAAN PERFORASI</h3>
    					<table border=0 style="100%" cellpadding=0 cellspacing=0>
    						<tr>
    							<td width="5%">&nbsp;</td>
    							<td valign="top">Kepada Yth.&nbsp;&nbsp;</td><td valign="top">:&nbsp;&nbsp;</td>
                  <td>KEPALA <?=strtoupper($system_params[2])?><br />
                    <?=strtoupper($system_params[7])." ".strtoupper($system_params[6]);?>
                  </td></tr>
    						</tr>    						
    					</table>
    				</td>
    				<td valign="top" style="border-bottom:1px solid #000;padding:10px;">
              <br /><br />
    					Tanggal :<br />
              <?=indo_date_format($row['tgl_permohonan'],'longDate');?>
    				</td>
    			</tr>    			
    			<tr>
    				<td colspan="3" style="padding:20px;">
    					Mohon agar dapat diperforasi sebagai berikut :
    				</td>
    			</tr>
    		</table>
    		<table class="report" cellspacing=0>
    			<thead>
    				<tr>
    					<td align="center" width="1%" rowspan="2" style="padding:30px!important;">No.</td>
    					<td align="center" rowspan="2">Jenis dan Nomor Urut</td>
    					<td align="center" rowspan="2">Kode</td>
              <td align="center" rowspan="2">Nilai Lembar</td>
              <td align="center" colspan="3">Banyaknya</td>
    				</tr>
            <tr>
              <td align="center" style="border-bottom:none;">Jumlah Blok</td>
              <td align="center" style="border-bottom:none;">Isi Blok</td>
              <td align="center" style="border-bottom:none;">Jumlah Lembar</td>
            </tr>
    			</thead>
    			<tbody>
    				<?php
              echo "<tr>
              <td  valign='top' align='center' style='padding:10px;'>1.</td>
              <td  valign='top' style='padding:10px;'>".$row['nm_rekening']." - ".$row['no_skrd']."</td>
              <td  valign='top' align='center' style='padding:10px;'>".$row['kd_karcis']."</td>
              <td  valign='top' align='right' style='padding:10px;'>".number_format($row['nilai_per_lembar'])."</td>
              <td  valign='top' align='right' style='padding:10px;'>".number_format($row['jumlah_blok'])."</td>
              <td  valign='top' align='right' style='padding:10px;'>".number_format($row['isi_per_blok'])."</td>
              <td  valign='top' align='right' style='padding:10px;'>".number_format($row['jumlah_lembar'])."</td>
              </tr>";
    				?>
            <tr style="height:250px;">
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
              <td style="border-top:none!important;"></td>
            </tr>
    			</tbody>
    		</table>
        <table style="border:1px solid #000;border-top:none;" cellpadding=8 cellspacing=0 width="100%">
          <tr>
            <td align="center">
              Disetujui oleh,
              <br /><br /><br /><br /><br /><br /><br />
              <b><u><?=$system_params[25];?></u></b><br />
              NIP. <?=$system_params[26];?>
            </td>
            <td align="center">
              Diperiksa oleh,
              <br /><br /><br /><br /><br /><br /><br />
              <b><u><?=$system_params[28];?></u></b><br />
              NIP. <?=$system_params[29];?>
            </td>
            <td align="center">
              Pemohon,
              <br /><br /><br /><br /><br /><br /><br />
              <b><u><?=$row['nm_pemohon'];?></u></b><br />
              NIP. <?=$row['nip_pemohon'];?>
            </td>
          </tr>
        </table>
  		</div>
      <?php
        }
        else
        {
          echo "<center><font color='red'>data tidak ditemukan!</font></center>";
        }
      ?>
 	</body>
</html>