<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_berita_acara = $_GET['id'];  

  $sql = "SELECT id_berita_acara,nm_pihak_kesatu,nip_pihak_kesatu,jbt_pihak_kesatu,
          nm_pihak_kedua,nip_pihak_kedua,jbt_pihak_kedua,tgl_berita_acara,
          no_surat_permohonan,tgl_surat_permohonan,
          no_berita_acara FROM app_ba_stbb WHERE id_berita_acara='".$id_berita_acara."'";
  
	$result = $db->Execute($sql);
  $n_ba = $result->RecordCount();

  if($n_ba>0)
  {
    $row = $result->FetchRow();   
    $system_params = $global->get_system_params();


    $x_tgl_ba = explode('-',$row['tgl_berita_acara']);
    $hari = get_dayName($row['tgl_berita_acara']);
    $tgl = $x_tgl_ba[2];
    $bln = get_monthName($x_tgl_ba[1]);
    $thn = $x_tgl_ba[0];

  }


?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Berita Acara Serah Terima Benda Berharga</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
  	</head>
  	<body>
      <?php
        if($n_ba>0)
        {
      ?>
  		<div style="padding:10px;">
    		<table style="border:1px solid #000" cellpaddding=0 cellspacing=0 width="100%">
          <tr>
            <td width="40%" style="border-right:1px solid #000;">
              <table width="100%">
                <tr>
                  <td width="20%"><img src="../../img/logo_pemkot_bekasi.png" width="60"/></td>
                  <td valign="top" align="center">                    
                    <br />
                    <h4>
                      PEMERINTAH <?=strtoupper($system_params[7]." ".$system_params[6]);?><br />
                      <?=strtoupper($system_params[2])?>
                    </h4>
                    <small><?=$system_params[3];?></small>
                    <h4><?=strtoupper($system_params[6]);?></h4>
                    <br />
                  </td>
                </tr>
              </table>
            </td>
            <td align="center" style="border-right:1px solid #000;" valign="top">
              <br /><br />
              <h4>BERITA ACARA<br />
                SERAH TERIMA BENDA BERHARGA
              </h4>
            </td>
            <td align="center" valign="top">
              <br /><br />
              No. : <br /><font style="font-weight:bold;font-size:1.4em"><?=$row['no_berita_acara'];?></font>
            </td>
          </tr>
        </table>
        <div style="border-left:1px solid #000;border-right:1px solid #000;padding:10px;">

          <table width="100%">
            <tr><td colspan="3">
              <?php
                echo "Pada hari ini ".$hari." tanggal ".$tgl." bulan ".$bln." tahun ".$thn.", Kami yang bertanda tangan di bawah ini :";
              ?>
            </td></tr>
            <tr><td width="2%">1.</td><td width="8%">Nama</td><td> : <?=$row['nm_pihak_kesatu'];?></td></tr>
            <tr><td></td><td>NIP</td><td> : <?=$row['nip_pihak_kesatu'];?></td></tr>
            <tr><td></td><td>Jabatan</td><td> : <?=$row['jbt_pihak_kesatu'];?></td></tr>
            <tr><td colspan="3">Selanjutnya disebut sebagai PIHAK KESATU
            <br /><br /></tr>
            <tr><td>2.</td><td width="8%">Nama</td><td> : <?=$row['nm_pihak_kedua'];?></td></tr>
            <tr><td></td><td>NIP</td><td> : <?=$row['nip_pihak_kedua'];?></td></tr>
            <tr><td></td><td>Jabatan</td><td> : <?=$row['jbt_pihak_kedua'];?></td></tr>
            <tr><td colspan="3">Selanjutnya disebut sebagai PIHAK KEDUA
            <br /><br /></tr>
            <tr><td colspan="3">
              <?php echo "PIHAK KESATU telah menyerahkan Benda Berharga berdasarkan Surat Permohonan Perforasi Nomor ".$row['no_surat_permohonan']." tanggal
                         ".indo_date_format($row['tgl_surat_permohonan'],'longDate')." kepada PIHAK KEDUA<br />
                         Adapun Benda Berharga yang DISERAHTERIMAKAN sebagai berikut :";?>
            </td></tr>
          </table>
        </div>
        <table class="report2" style="border-top:1px solid #000;" cellspacing=0>
          <thead>
            <tr>
              <td align="center" rowspan="2">No.</td>
              <td align="center" rowspan="2">Nama Benda Berharga</td>
              <td align="center" rowspan="2">Kode Benda Berharga</td>
              <td align="center" rowspan="2">Nilai Per Lembar</td>
              <td align="center" colspan="4" style="border-bottom:1px solid #000">Jumlah Yang Diterima</td>
            </tr>
            <tr>
              <td align="center" style="border-bottom:none;">Jumlah Blok</td>
              <td align="center" style="border-bottom:none;">Jumlah Lembar Per Blok</td>
              <td align="center" style="border-bottom:none;">Jumlah Lembar</td>
              <td align="center" style="border-bottom:none;">Nomor Seri</td>
            </tr>
          </thead>
          <tbody>
            <?php

            $sql = "SELECT b.nm_rekening,b.kd_karcis,b.nilai_per_lembar,b.jumlah_blok,b.isi_per_blok,b.jumlah_lembar,
                    b.no_seri FROM app_dtl_ba_stbb as a LEFT JOIN app_permohonan_karcis as b ON (a.fk_permohonan=b.id_permohonan) 
                    WHERE a.fk_berita_acara='".$row['id_berita_acara']."'";
            
            $result = $db->Execute($sql);

            $no = 0;
            while($row2=$result->FetchRow())
            {
              $no++;
              echo "
              <tr>
                <td valign='top' align='center' style='padding:10px;'>".$no.".</td>
                <td valign='top' style='padding:10px;'>Karcis ".$row2['nm_rekening']."</td>
                <td valign='top' align='center' style='padding:10px;'>".$row2['kd_karcis']."</td>
                <td valign='top' align='right' style='padding:10px;'>".number_format($row2['nilai_per_lembar'],0,',','.')."</td>
                <td valign='top' align='right' style='padding:10px;'>".number_format($row2['jumlah_blok'],0,',','.')."</td>
                <td valign='top' align='right' style='padding:10px;'>".number_format($row2['isi_per_blok'],0,',','.')."</td>
                <td valign='top' align='right' style='padding:10px;'>".number_format($row2['jumlah_lembar'],0,',','.')."</td>
                <td valign='top' align='center' style='padding:10px;'>".$row2['no_seri']."</td>
              </tr>";
            }
            ?>           
          </tbody>
        </table>
        <table style="border:1px solid #000;border-top:none;" cellpadding=8 cellspacing=0 width="100%">
          <tr>
            <td colspan="2" style="padding-left:13px">Demikian Berita Acara Serah Terima Benda Berharga ini dibuat menurut sebenarnya untuk dipergunakan seperlunya.
          </tr>
          <tr>
            <td align="center" width="50%">
              Yang Menerima,<br />
              PIHAK KEDUA,
              <br /><br /><br /><br /><br /><br /><br />
              <b><u><?=$row['nm_pihak_kedua'];?></u>
            </td>
            <td align="center">
              Yang Menyerahkan,<br />
              PIHAK KESATU,
              <br /><br /><br /><br /><br /><br /><br />
              <b><u><?=$row['nm_pihak_kesatu'];?></u>
            </td>
          </tr>
        </table>
        <div style="margin-top:5px">MODEL : DPD-56</div>
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