<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

header("Content-Type: application/force-download");
header("Cache-Control: no-cache, must-revalidate");
header("content-disposition: attachment;filename=daftar-skrd".date('dmY').".xls");


  $global = new global_obj($db);

  	$kd_rek = $_GET['kd_rek'];
	$src_status = $_GET['sts'];
  $src_tipe = $_GET['type'];
  $src_tgl_skrd_awal = $_GET['dt1'];
  $src_tgl_skrd_akhir = $_GET['dt2'];

  include_once "data_preparation.php";

?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Daftar Surat Ketetapan Retribusi Daerah</title>
  	</head>
  	<body>
      <div style="margin:10px;">
        <h3 align="center">DAFTAR SURAT KETETAPAN RETRIBUSI DAERAH</h3>
        <br />
        <?php
        echo "
        <table>
        <tr><td colspan=3>Status : ".$lbl_status.", Tipe : ".$lbl_tipe."</td></tr>        
        </table>
        <table class='report' cellpadding='0' border='1' cellspacing='0'>
          <thead>
            <tr>
              <th>No.</th>
              <th>No. SKRD</th>
              <th>Tgl. SKRD</th>
              <th>Jenis Retribusi</th>
              <th>Masa Retribusi</th>
              <th>Wajib Retribusi</th>
			   <th>Kecamatan</th>
			    <th>UPTB</th>";
              if($src_tipe=='2'){
                echo "<th>Total Perforasi</th>";
              }
              echo "<th>Total Retribusi</th>";
              if($src_status!='1'){
                echo "<th>Kd. Billing</th>";
              }
              echo "
            </tr>           
          </thead>
          <tbody>";
            if($list_of_data->RecordCount()>0)
            {
              $no = 0;
              $grand_retribusi = 0;
              $grand_nilai_perforasi = 0;
              while($row=$list_of_data->FetchRow())
              {
                foreach($row as $key => $val){
                          $key=strtolower($key);
                          $$key=$val;
                      }

                $no++;
                $bg = ($no%2==0?"even":"odd");
                echo "<tr>
                <td align='center'>".$no."</td>
                <td align='center'>".$no_skrd."</td>
                <td align='center'>".$tgl_skrd."</td>
                <td>".$kd_rekening." - ".$nm_rekening."</td>
                <td>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
                <td>".$npwrd." - ".$wp_wr_nama."</td>
				<td>".$wp_wr_camat."</td>
				<td>".$user_input."</td>";
                if($src_tipe=='2'){
                  echo "<td align='right'>".number_format($nilai_total_perforasi,0,'.','.')."</td>";
                }                
                echo "<td align='right'>".number_format($total_retribusi,0,'.','.')."</td>";
                if($src_status!='1'){
                  echo "<td align='center'>".$kd_billing."'</td>";
                }
                echo "</tr>";
                $grand_retribusi += $total_retribusi;
                if($src_tipe=='2'){
                  $grand_nilai_perforasi += $nilai_total_perforasi;
                }
              }
              echo "
              <tfoot>
                <tr>
                  <td colspan='8' align='right'><b>TOTAL</b></td>";
                  if($src_tipe=='2'){
                    echo "<td align='right'>".number_format($grand_nilai_perforasi,0,'.','.')."</td>";
                  }
                  echo "<td align='right'>".number_format($grand_retribusi,0,'.','.')."</td>";
                  if($src_status!='1'){
                    echo "<td></td>";
                  }
                echo "</tr>
              </tfoot>";
            }
            else
            {
              echo "<tr><td colspan='10' align='center'>Data tidak tersedia !</td></tr>";
            }           
          echo "</tbody>
        </table>";
        ?>
        <footer style="margin-top:10px;">
          Printed on <?=date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIZATION_ACR." ".$_CITY;?>
        </footer>
      </div>      
 	</body>
</html>