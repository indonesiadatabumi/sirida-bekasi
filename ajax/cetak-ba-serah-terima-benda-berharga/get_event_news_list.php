<?php
    
    require_once("inc/init.php");	    

    $key = strtolower($_POST['searched_key']);
    $no_ba = (is_numeric($key)?$key:0);

    $curr_year = date('Y');

    $sql = "SELECT a.id_berita_acara,no_berita_acara,a.nm_pihak_kesatu,a.nm_pihak_kedua,to_char(a.tgl_berita_acara,'dd-mm-yyyy') as tgl_berita_acara,
            (SELECT COUNT(1) FROM app_dtl_ba_stbb as x WHERE x.fk_berita_acara=a.id_berita_acara) as jml_perforasi
             FROM app_ba_stbb as a WHERE(a.thn_retribusi='".$curr_year."') AND ((a.no_berita_acara='".$no_ba."') 
            OR (lower(a.nm_pihak_kesatu) LIKE '%".strtolower($key)."%') OR (lower(a.nm_pihak_kedua) LIKE '%".strtolower($key)."%')) ORDER BY a.no_berita_acara ASC";

    
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
            <td align='center'>".$no_berita_acara."</td>
            <td>".$nm_pihak_kesatu."</td>
            <td>".$nm_pihak_kedua."</td>
            <td align='center'>".$tgl_berita_acara."</td>
            <td align='right'>".$jml_perforasi."</td>
            <td align='center'>
            <a href='javascript:;' title='Pilih' class='btn btn-xs btn-default' id='chose_".$no."' onclick=\"choose('".$id_berita_acara."','".$no_berita_acara."',
                                                                                                                    '".$nm_pihak_kesatu."','".$nm_pihak_kedua."',
                                                                                                                    '".$tgl_berita_acara."');\">
                <i class='fa fa-check'></i>
            </a>
            </td>
            </tr>";
        }
    }
    else
    {
        echo "<tr>
        <td colspan='7' align='center'>data tidak tersedia</td>
        </tr>";
    }

?>