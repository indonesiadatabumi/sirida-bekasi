<?php
require_once("inc/init.php");
require_once("../../lib/fpdf/MC_TABLE.php");
require_once("../../helpers/date_helper.php");

$kd_rekening = $_GET['rek'];
	$kecamatan = $_GET['kec'];
	$tgl1 = us_date_format($_GET['tgl1']);
	$tgl2 = us_date_format($_GET['tgl2']);

	// laporan retribusi semua muncul dan data pasar tidak muncul
	if($kd_rekening=='' and $kecamatan=='')
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd) as b ON (a.kd_billing=b.kd_billing)";
	}
	// laporan retribusi pasar muncul
	else if($kd_rekening=='4120120'){
	$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM payment_retribusi_pasar as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama,wp_wr_camat,kd_rekening FROM app_skrd_pasar) as b ON (a.kd_billing=b.kd_billing) ";
	}
	
	
	else 
	{
	$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama,wp_wr_camat,kd_rekening FROM app_skrd) as b ON (a.kd_billing=b.kd_billing) ";
		//$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
	//			INNER JOIN (SELECT x.kd_billing,x.no_skrd,x.wp_wr_nama FROM app_skrd as x WHERE(x.kd_rekening='".$kd_rekening."' and x.wp_wr_camat='".$kecamatan."')) as b ON (a.kd_billing=b.kd_billing)";
	}
	//$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
if($kd_rekening=='4120120'){

	if($kecamatan==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."'";
	} else {$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."' and b.wp_wr_camat='".$kecamatan."'";}
	
}else if($kd_rekening=='' and $kecamatan==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
	}
else
{
//$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";
if($kecamatan==''){
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."'";
	} else	{$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."' and  b.kd_rekening='".$kd_rekening."' and b.wp_wr_camat='".$kecamatan."'";}
}

	$list_sql .= $cond;
    var_dump($list_sql);die;
	
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();
	
	$sql = "SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='" . $kd_rekening . "')";
	$jenis_retribusi = $db->getOne($sql);
class PDF extends MC_TABLE
{
    // Page footer
    // function Footer()
    // {
    //  // Position at 1.5 cm from bottom
    //  $this->SetY(-15);
    //  // Arial italic 8
    //  $this->SetFont('Arial','I',8);
    //  // Page number
    //  $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    // }

    function subWrite($h, $txt, $link = '', $subFontSize = 12, $subOffset = 0)
    {
        // resize font
        $subFontSizeold = $this->FontSizePt;
        $this->SetFontSize($subFontSize);

        // reposition y
        $subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
        $subX        = $this->x;
        $subY        = $this->y;
        $this->SetXY($subX, $subY - $subOffset);

        //Output text
        $this->Write($h, $txt, $link);

        // restore y position
        $subX        = $this->x;
        $subY        = $this->y;
        $this->SetXY($subX,  $subY + $subOffset);

        // restore font size
        $this->SetFontSize($subFontSizeold);
    }
}

$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);

$pdf->AddPage();

$pdf->setFont('Arial', 'B', 11);
$pdf->cell(0, 5, 'REALISASI PENERIMAAN ' . strtoupper($jenis_retribusi), '', 1, 'C');
$pdf->setFont('Arial', '', 11);
$pdf->cell(0, 5, 'PERIODE ' . mix_2Date($tgl1, $tgl2), '', 1, 'C');

$pdf->ln(5);
//header

$pdf->SetFillColor(204, 204, 204);

$pdf->setFont('Arial', '', 10);

$pdf->cell(10, 4, '', 'LT', 0, 'C', 1);
$pdf->cell(50, 4, '', 'LT', 0, 'C', 1);
$pdf->cell(30, 4, '', 'LT', 0, 'C', 1);
$pdf->cell(20, 3, '', 'LT', 0, 'C', 1);
$pdf->cell(20, 3, '', 'LT', 0, 'C', 1);
$pdf->cell(60, 4, '', 'LT', 0, 'C', 1);
$pdf->cell(32, 3, '', 'LT', 0, 'C', 1);
$pdf->cell(0, 3, '', 'LRT', 1, 'C', 1);

$pdf->setY(29);
$pdf->cell(10, 5, 'No.', 'L', 0, 'C', 1);
$pdf->cell(50, 5, 'Jenis Retribusi', 'L', 0, 'C', 1);
$pdf->cell(29.8, 5, 'Kode Rekening', 'L', 0, 'C', 1);

$pdf->setY(28);
$pdf->setX(100);
$pdf->cell(20, 4, 'Masa', 'L', 0, 'C', 1);
$pdf->cell(19.8, 4, 'No.', 'L', 0, 'C', 1);

$pdf->setY(29);
$pdf->setX(140);
$pdf->cell(60, 5, 'Nama WR', 'L', 0, 'C', 1);

$pdf->setY(28);
$pdf->setX(200);
$pdf->cell(32, 4, 'No. SSRD/', 'L', 0, 'C', 1);
$pdf->cell(0, 4, 'Penerimaan', 'LR', 1, 'C', 1);

$pdf->setY(34);
$pdf->cell(10, 4, '', 'L', 0, 'C', 1);
$pdf->cell(50, 4, '', 'L', 0, 'C', 1);
$pdf->cell(29.8, 4, '', 'L', 0, 'C', 1);

$pdf->setY(32);
$pdf->setX(100);

$pdf->cell(20, 4, 'Retribusi', 'L', 0, 'C', 1);
$pdf->cell(19.8, 4, 'SKRD', 'L', 0, 'C', 1);

$pdf->setY(34);
$pdf->setX(140);

$pdf->cell(60, 4, '', 'L', 0, 'C', 1);

$pdf->setY(32);
$pdf->setX(200);
$pdf->cell(32, 4, 'STS', 'L', 0, 'C', 1);
$pdf->cell(0, 4, 'Retribusi (Rp.)', 'LR', 1, 'C', 1);

$pdf->setY(36);
$pdf->setX(100);
$pdf->cell(20, 2, '', 'L', 0, 'C', 1);
$pdf->cell(19.8, 2, '', 'L', 0, 'C', 1);

$pdf->setX(200);
$pdf->cell(32, 2, '', 'L', 0, 'C', 1);
$pdf->cell(0, 2, '', 'LR', 0, 'C', 1);

$pdf->setY(38);
if ($list_of_data->RecordCount() > 0) {
    $widths = array(10, 50, 30, 20, 20, 60, 32, 37.4);
    $pdf->setWidths($widths);

    $no = 0;
    $total_retribusi = 0;
    while ($row = $list_of_data->FetchRow()) {
        $no++;
        foreach ($row as $key => $val) {
            $key = strtolower($key);
            $$key = $val;
        }
        $datas = array(
            array($no, 'C'),
            array($nm_rekening, 'L'),
            array($kd_rekening, 'C'),
            array(get_monthName($bln_retribusi) . " " . $thn_retribusi, 'C'),
            array($no_skrd, 'C'),
            array($wp_wr_nama, 'L'),
            array($ntpd, 'C'),
            array(number_format($total_bayar), 'R')
        );
        $pdf->row($datas);
        $total_retribusi += $total_bayar;
    }
    $pdf->setFont('Arial', 'B', 10);
    $pdf->cell(222, 6, 'TOTAL', 'LB', 0, 'R');
    $pdf->cell(0, 6, number_format($total_retribusi), 'LBR', 1, 'R');
} else {
    $widths = array(259.5);
    $pdf->setWidths($widths);
    $data = array(array('Data tidak tersedia!', 'C'));
    $pdf->row($data);
}

$pdf->setFont('Arial', '', 10);
$pdf->cell(0, 6, 'Printed on ' . date('d-m-Y H:i:s') . " from " . $_SITE_TITLE . " " . $_ORGANIZATION_ACR . " " . $_CITY);
$pdf->Output();
