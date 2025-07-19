<?php
    
    require_once("inc/init.php");	    


    $key = strtolower($_POST['searched_key']);
    $no_skrd = (is_numeric($key)?$key:0);

    $sql = "SELECT a.id_skrd,a.no_skrd,a.kd_rekening,a.nm_rekening,a.wp_wr_nama,b.kd_karcis,
            b.id_permohonan,b.tgl_permohonan,b.nilai_total_perforasi,b.isi_per_blok,b.nilai_per_lembar FROM app_skrd as a 
            INNER JOIN (SELECT id_permohonan,kd_karcis,fk_skrd,tgl_permohonan,isi_per_blok,nilai_per_lembar,nilai_total_perforasi
                        FROM app_permohonan_karcis) as b 
            ON (a.id_skrd=b.fk_skrd) WHERE(a.tipe_retribusi='2') AND ((a.no_skrd='".$no_skrd."') 
            OR (lower(a.nm_rekening) LIKE '%".strtolower($key)."%') OR (lower(b.kd_karcis) LIKE '%".strtolower($key)."%')) ORDER BY a.no_skrd ASC";

    $result = $db->Execute($sql);
    if(!$result)
    {
        echo $db->ErrorMsg();
    }

    $no = 0;
    if($result->RecordCount()>0)
    {
        while($row = $result->FetchRow())
        {
        	$no++;
            foreach($row as $key => $val){
                  $key=strtolower($key);
                  $$key=$val;
              }
            echo "<tr>
            <td align='center'>".$no."</td>
            <td align='center'>".$no_skrd."</td>
            <td align='center'>".$kd_karcis."</td>
            <td>".$nm_rekening."</td>
            <td>".$wp_wr_nama."</td>
            <td align='right'>".number_format($nilai_per_lembar)."<br />".number_format($nilai_total_perforasi)."</td>
            <td>".$tgl_permohonan."</td>
            <td align='center'>
            <a href='javascript:;' title='Pilih' class='btn btn-xs btn-default' id='chose_".$no."' onclick=\"choose('".$id_permohonan."','".$nm_rekening."','".$wp_wr_nama."','".$isi_per_blok."','".number_format($nilai_per_lembar)."');\">
                <i class='fa fa-check'></i>
            </a>
            </td>
            </tr>";
        }
    }
    else
    {
        echo "<tr>
        <td colspan='8' align='center'>data tidak tersedia</td>
        </tr>";
    }

?>