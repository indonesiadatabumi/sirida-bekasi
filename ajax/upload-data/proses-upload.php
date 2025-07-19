<?php
session_start();
require_once("db_connect.php");
require_once("../../lib/DML.php");
require_once("../../lib/PHPExcel/PHPExcel.php");
require_once("../../lib/global_obj.php");

$bln_retribusi = $_POST['bln_retribusi'];
$thn_retribusi = $_POST['tahun_retribusi'];
$tgl_skrd = $_POST['tgl_skrd'];
// $dasar_pengenaan = $_POST['dasar_pengenaan'];
// $jns_retribusi = $_POST['jns_retribusi'];

$arr_data = array();
$global = new global_obj($db);
$DML1 = new DML('app_skrd', $db);
$DML2 = new DML('app_nota_perhitungan', $db);

$tgl_skrd2 = date("Y-m-d", strtotime($tgl_skrd));

$namaFile = basename($_FILES['fileExcel']['name']);

if (isset($_FILES['fileExcel']['name']) && $namaFile <> '') {
    $path = $_FILES["fileExcel"]["tmp_name"];
    $object = PHPExcel_IOFactory::load($path);

    foreach ($object->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++) {
            $no_skrd = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $npwrd = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $nama_wpwr = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $korek = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $no_uji = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $nopol = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
            $total_retribusi = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

            $korek2 = str_replace(".", "", $korek);
            $id_skrd = $global->get_incrementID('app_skrd', 'id_skrd');
            $nm_rekening = $db->getOne("SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='" . $korek2 . "')");
            // $nm_wp_wr = addslashes($nama_wpwr);
            $nm_wp_wr = str_replace("'", "", $nama_wpwr);

            /*
            $temp_data[] = array(
                'no_skrd' => $no_skrd,
                'bln_retribusi' => $bln_retribusi,
                'thn_retribusi' => $thn_retribusi,
                'tipe_retribusi' => '1',
                'npwrd' => $npwrd,
                'wp_wr_nama' => $nama_wpwr,
                'wp_wr_alamat' => $alamat,
                'wp_wr_kabupaten' => 'BEKASI',
                'kd_rekening' => $korek2,
                'nm_rekening' => '',
                'user_input' => $_SESSION['username'],
                'tgl_input' => date('Y-m-d H:i:s'),
                'tgl_skrd' => $tgl_skrd,
                'status_ketetapan' => '0',
                'id_skrd' => ''
            );
            */
            
            $arr_data['no_skrd'] = $no_skrd;
            $arr_data['bln_retribusi'] = $bln_retribusi;
            $arr_data['thn_retribusi'] = $thn_retribusi;
            $arr_data['tipe_retribusi'] = '1';
            $arr_data['tgl_input'] = date('Y-m-d H:i:s');
            $arr_data['user_input'] = $_SESSION['username'];
            $arr_data['status_ketetapan'] = '0';
            $arr_data['status_bayar'] = '0';
            $arr_data['status_lunas'] = '0';
            $arr_data['npwrd'] = $npwrd;
            $arr_data['wp_wr_nama'] = $nm_wp_wr;
            $arr_data['wp_wr_alamat'] = $alamat;
            $arr_data['kd_rekening'] = $korek2;
            $arr_data['nm_rekening'] = $nm_rekening;
            $arr_data['id_skrd'] = $id_skrd;
            $arr_data['no_uji'] = $no_uji;
            $arr_data['no_polisi'] = $nopol;
            $arr_data['tgl_skrd'] = $tgl_skrd2;

            //app_skrd
            $result = $DML1->save($arr_data);
            if (!$result) {
                $db->RollbackTrans();
                echo "
                <script>
                    alert('Upload data gagal, ada kesalahan pada baris ".$row."');
                    location.href='../../index.php#ajax/upload-data-dishub.php';
                    exit();
                </script>
                ";
                die();
            }

            // app nota perhitungan
            $arr_data2['bln_retribusi'] = $bln_retribusi;
            $arr_data2['thn_retribusi'] = $thn_retribusi;
            $arr_data2['npwrd'] = $npwrd;
            $arr_data2['kd_rekening'] = $korek2;
            $arr_data2['nm_rekening'] = $nm_rekening;
            $arr_data2['jenis_ketetapan'] = 'SKRD';
            $arr_data2['imb'] = '0';
            $arr_data2['total_retribusi'] = $total_retribusi;
            $arr_data2['fk_skrd'] = $id_skrd;
            $arr_data2['no_nota_perhitungan'] = $no_skrd;
            $arr_data2['dasar_pengenaan'] = '';
            $arr_data2['keterangan'] = '';

            //check app_nota_perhitungan availability
            $no_nota = $arr_data2['no_nota_perhitungan'];
            $sql = "SELECT id_nota FROM app_nota_perhitungan WHERE kd_rekening='" . $korek2 . "' AND thn_retribusi='" . $thn_retribusi . "' 
				AND no_nota_perhitungan='" . $no_nota . "'";
            $id_nota = $db->getOne($sql);

            if (is_null($id_nota) or empty($id_nota)) {
                $id_nota = $global->get_incrementID('app_nota_perhitungan', 'id_nota');

                $arr_data2['id_nota'] = $id_nota;

                //app_note_perhitungan
                $result = $DML2->save($arr_data2);
                if (!$result) {
                    $db->RollbackTrans();
                    die('failed2');
                }
            } else {

                $cond = "id_nota='" . $id_nota . "'";
                $result = $DML2->update($arr_data2, $cond);
                if (!$result) {
                    $db->RollbackTrans();
                    die('failed3');
                }
            }
        }
    }
    $db->CommitTrans();

    echo "
        <script>
            alert('Data berhasil disimpan');
            location.href='../../index.php#ajax/upload-data-dishub.php';
            exit();
        </script>
    ";
} else {
    echo "
    <script>
        alert('Silahkan pilih file data');
        location.href='../../index.php#ajax/upload-data-dishub.php';
        exit();
    </script>
";
}
