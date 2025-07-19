<?php
session_start();
require_once("db_connect.php");
require_once("../../lib/DML.php");
require_once("../../lib/PHPExcel/PHPExcel.php");
require_once("../../lib/global_obj.php");

$arr_data = array();
$arr_data2 = array();

$global = new global_obj($db);
$DML1 = new DML('app_skrd', $db);
$DML2 = new DML('app_nota_perhitungan', $db);

// $tgl_skrd2 = date("Y-m-d", strtotime($tgl_skrd));

$namaFile = basename($_FILES['fileExcel']['name']);

if (isset($_FILES['fileExcel']['name']) && $namaFile <> '') {
    $path = $_FILES["fileExcel"]["tmp_name"];
    $object = PHPExcel_IOFactory::load($path);

    foreach ($object->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++) {
            $no_skrd = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $tgl_skrd = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $korek = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $masaretribusi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $npwrd = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $nama_wpwr = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $total_retribusi = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

            $UNIX_DATE = ($tgl_skrd - 25569) * 86400;
            $tgl_skrd2 = gmdate("Y-m-d", $UNIX_DATE);

            $pisah_korek = explode("-", $korek);
            $kd_rek = trim($pisah_korek[0]);
            $nm_rek = trim($pisah_korek[1]);

            $pisah_masaretribusi = explode(' ', $masaretribusi);
            $bln = $pisah_masaretribusi[0];
            switch ($bln) {
                case 'Juli':
                    $bln_retribusi = 7;
                    break;
                case 'Januari':
                    $bln_retribusi = 1;
                    break;
                case 'Februari':
                    $bln_retribusi = 2;
                    break;
                case 'Maret':
                    $bln_retribusi = 3;
                    break;
                case 'April':
                    $bln_retribusi = 4;
                    break;
                case 'Mei':
                    $bln_retribusi = 5;
                    break;
                case 'Juni':
                    $bln_retribusi = 6;
                    break;
                case 'Agustus':
                    $bln_retribusi = 8;
                    break;
                case 'September':
                    $bln_retribusi = 9;
                    break;
                case 'Oktober':
                    $bln_retribusi = 10;
                    break;
                case 'Nopember':
                    $bln_retribusi = 11;
                    break;
                case 'Desember':
                    $bln_retribusi = 12;
                    break;
                default:
                    $bln_retribusi = 0;
            }

            $thn_retribusi = $pisah_masaretribusi[1];

            // $korek2 = str_replace(".", "", $korek);
            $id_skrd = $global->get_incrementID('app_skrd', 'id_skrd');
            // $nm_rekening = $db->getOne("SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='" . $korek2 . "')");
            // $nm_wp_wr = mysqli_real_escape_string($nama_wpwr);

            $alamat_wpwr = $db->getOne("SELECT alamat_wp_wr FROM app_reg_wr WHERE npwrd='" . $npwrd . "'");

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
            $arr_data['wp_wr_nama'] = $nama_wpwr;
            $arr_data['wp_wr_alamat'] = $alamat_wpwr;
            $arr_data['kd_rekening'] = $kd_rek;
            $arr_data['nm_rekening'] = $nm_rek;
            $arr_data['id_skrd'] = $id_skrd;
            $arr_data['tgl_skrd'] = $tgl_skrd2;

            //app_skrd

            $result = $DML1->save($arr_data);

            if (!$result) {
                $db->RollbackTrans();
                echo "
                <script>
                    alert('Upload data gagal, ada kesalahan pada baris " . $row . "');
                    location.href='../../index.php#ajax/upload-data-pasar.php';
                    exit();
                </script>
                ";
                die();
            }

            // app nota perhitungan
            $arr_data2['bln_retribusi'] = $bln_retribusi;
            $arr_data2['thn_retribusi'] = $thn_retribusi;
            $arr_data2['npwrd'] = $npwrd;
            $arr_data2['kd_rekening'] = $kd_rek;
            $arr_data2['nm_rekening'] = $nm_rek;
            $arr_data2['jenis_ketetapan'] = 'SKRD';
            $arr_data2['imb'] = '0';
            $arr_data2['total_retribusi'] = $total_retribusi;
            $arr_data2['fk_skrd'] = $id_skrd;
            $arr_data2['no_nota_perhitungan'] = $no_skrd;
            $arr_data2['dasar_pengenaan'] = '';
            $arr_data2['keterangan'] = '';


            //check app_nota_perhitungan availability
            $no_nota = $arr_data2['no_nota_perhitungan'];
            $sql = "SELECT id_nota FROM app_nota_perhitungan WHERE kd_rekening='" . $kd_rek . "' AND thn_retribusi='" . $thn_retribusi . "' 
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
            location.href='../../index.php#ajax/upload-data-pasar.php';
            exit();
        </script>
    ";
} else {
    echo "
    <script>
        alert('Silahkan pilih file data');
        location.href='../../index.php#ajax/upload-data-pasar.php';
        exit();
    </script>
";
}
