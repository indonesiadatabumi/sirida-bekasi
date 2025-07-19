<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_skrd = $_GET['id'];

  $sql = "SELECT a.no_skrd,a.kd_rekening,a.nm_rekening,b.kd_karcis,b.nilai_per_lembar,b.id_permohonan,b.isi_per_blok
          FROM app_skrd as a INNER JOIN 
          (SELECT x.id_permohonan,x.fk_skrd,x.kd_karcis,x.nilai_per_lembar,x.isi_per_blok
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
    	<title><?php echo $_SITE_TITLE;?> - Kartu Persediaan Benda Berharga Per UKT</title>
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
            <td rowspan="2" width="40%" style="border-right:1px solid #000;">
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
            <td align="center" valign="middle" colspan="2" style="border-bottom:1px solid #000;" valign="top">
              <h4 style="margin:0">KARTU PERSEDIAAN BENDA BERHARGA PER UKT</h4>
            </td>
          </tr>
          <tr>
            <td style="border-right:1px solid #000;">
              <table border=0 style="100%" cellpadding=0 cellspacing=0>
                <tr>
                  <td>Kode Benda Berharga</td><td>&nbsp;&nbsp;: <?=$row['kd_karcis'];?></td></tr>
                </tr>
                <tr>                  
                  <td>Nama Benda Berharga</td><td>&nbsp;&nbsp;: Karcis <?=$row['nm_rekening'];?></td></tr>
                </tr>
                <tr>                  
                  <td>Kode Rekening</td><td>&nbsp;&nbsp;: <?=$row['kd_rekening'];?></td></tr>
                </tr>
              </table>
            </td>
            <td>
              <table border=0 style="100%" cellpadding=0 cellspacing=0>
                <tr>                  
                  <td>No. Kartu</td><td>&nbsp;&nbsp;:</td></tr>
                </tr>
                <tr>                  
                  <td>Nilai/Lembar</td><td>&nbsp;&nbsp;: Rp. <?=number_format($row['nilai_per_lembar']);?></td></tr>
                </tr>
                <tr>                  
                  <td>Halaman</td><td>&nbsp;&nbsp;:</td></tr>
                </tr>
              </table>
            </td>
          </tr>          
        </table>

    		<table class="report" cellspacing=0>
    			<thead>
    				<tr>
    					<th align="center">Tanggal</th>
    					<th align="center">Keterangan</th>
    					<th align="center">Keluar Blok</th>
              <th align="center">Masuk Blok</th>
              <th align="center">Nilai/Lembar (Rp)</th>
              <th align="center">Ref. Awal</th>
              <th align="center">Ref. Akhir</th>
              <th align="center">Sisa blok</th>
              <th align="center">Lembar</th>
              <th align="center">Jumlah Lembar</th>
              <th align="center">Nilai Uang (Rp.)</th>
    				</tr>            
    			</thead>
    			<tbody>
    				<?php
              $sql = "SELECT * FROM app_persediaan_benda_berharga WHERE fk_permohonan='".$row['id_permohonan']."' ORDER BY no_persediaan ASC";

              $result = $db->Execute($sql);
              
              while($row2 = $result->fetchRow())
              {
                
                $bold = ($row2['no_persediaan']=='1'?"style='font-weight:bold'":"");
                echo "
                <tr>
                <td ".$bold.">".indo_date_format($row2['tgl_persediaan'],'longDate')."</td>
                <td ".$bold.">".$row2['keterangan']."</td>
                <td align='right'>".number_format($row2['blok_keluar'])."</td>
                <td align='right'>".number_format($row2['blok_masuk'])."</td>
                <td align='right'>".number_format($row['nilai_per_lembar'])."</td>
                <td align='center'>".__number_format(sprintf('%07s',$row2['no_awal']),0,',','.')."</td>
                <td align='center'>".__number_format(sprintf('%07s',$row2['no_akhir']),0,',','.')."</td>
                <td align='right'>".number_format($row2['sisa_blok'])."</td>
                <td align='right'>".number_format($row['isi_per_blok'])."</td>
                <td align='right'>".number_format($row2['jumlah_lembar'])."</td>
                <td align='right'>".number_format($row2['nilai_uang'])."</td>
                </tr>";
              }
    				?>
            
    			</tbody>
    		</table>
        <table style="border:1px solid #000;border-top:none;" cellpadding=8 cellspacing=0 width="100%">
          <tr>
            <td align="center">              
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