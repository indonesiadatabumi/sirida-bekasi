<?php
	require_once("inc/init.php");  
    require_once("../../lib/DML.php");
    require_once("../../lib/global_obj.php");
    require_once("../../lib/fpdf/MC_TABLE.php");
    require_once("../../helpers/mix_helper.php");
    require_once("../../helpers/date_helper.php");

    $global = new global_obj($db);

	$kd_rek = $_GET['kd_rek'];
    $src_status = $_GET['sts'];
    $src_tipe = $_GET['type'];
    $src_tgl_skrd_awal = $_GET['dt1'];
    $src_tgl_skrd_akhir = $_GET['dt2'];

    include_once "data_preparation.php";

	class PDF extends MC_TABLE
	{
		// Page footer
		// function Footer()
		// {
		// 	// Position at 1.5 cm from bottom
		// 	$this->SetY(-15);
		// 	// Arial italic 8
		// 	$this->SetFont('Arial','I',8);
		// 	// Page number
		// 	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		// }

        function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0)
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

	$pdf = new PDF('L','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,10,10);

    $pdf->AddPage();

    $pdf->setFont('Arial','B',11);
    $pdf->cell(0,5,'DAFTAR SURAT KETETAPAN RETRIBUSI','',1,'C');
    $pdf->setFont('Arial','',11);    

    $pdf->ln(5);
    //header

    $pdf->SetFillColor(204,204,204);

    $pdf->setFont('Arial','',9);    
    
    $dyn_width = 80;

    if($src_tipe=='2')
        $dyn_width -= 12;
    if($src_status!='1')
        $dyn_width -= 15;

    $pdf->cell(0,5,'Status : '.$lbl_status.', Tipe : '.$lbl_tipe,'',2,'L');

    $pdf->cell(10,5,'No.','LT',0,'C',1);
    $pdf->cell(16,5,'No. SKRD','LT',0,'C',1);
    $pdf->cell(22,5,'Tgl. SKRD','LT',0,'C',1);
    $pdf->cell($dyn_width,5,'Jenis Retribusi','LT',0,'C',1);
    $pdf->cell(28,5,'Masa Retribusi','LT',0,'C',1);
    $pdf->cell($dyn_width,5,'Wajib Retribusi','LT',0,'C',1);
    
    if($src_tipe=='2'){
        $pdf->cell(24,5,'Total Perforasi','LT',0,'C',1);
    }
    

    $pdf->cell(($src_status=='1'?0:23.5),5,'Total Retribusi','LTR',($src_status=='1'?1:0),'C',1);
    
    if($src_status!='1'){
        $pdf->cell(0,5,'Kd. Billing','LTR',1,'C',1);
    }
    
    if($list_of_data->RecordCount()>0)
    {

        $widths = array(10,16,22,$dyn_width,28,$dyn_width);

        if($src_tipe=='2'){
            $widths[count($widths)] = 24;
        }

        $widths[count($widths)] = 23.5;

        if($src_status!='1'){
            $widths[count($widths)] = 30;
        }

        $pdf->setWidths($widths);

        $no = 0;
        $grand_retribusi = 0;
        $grand_nilai_perforasi = 0;
        
        while($row=$list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key => $val){
                $key=strtolower($key);
                $$key=$val;
            }

            $datas = array(
                            array($no,'C'),
                            array($no_skrd,'C'),
                            array($tgl_skrd,'C'),
                            array($kd_rekening.' - '.$nm_rekening,'L'),
                            array(get_monthName($bln_retribusi)." ".$thn_retribusi,'L'),
                            array($npwrd." - ".$wp_wr_nama,'L'),
                          );
            if($src_tipe=='2'){
                $datas[count($datas)] = array(number_format($nilai_total_perforasi),'R');
            }
            $datas[count($datas)] = array(number_format($total_retribusi),'R');
            if($src_status!='1'){
                $datas[count($datas)] = array($kd_billing,'C');
            }

            $pdf->row($datas);
            $grand_retribusi += $total_retribusi;
            if($src_tipe=='2'){
              $grand_nilai_perforasi += $nilai_total_perforasi;
            }
        }

        $pdf->setFont('Arial','B',9);
        $width = 236;
        
        if($src_tipe=='2')
            $width -= 24;
        if($src_status!='1')
            $width -= 30;

        $pdf->cell($width,6,'TOTAL','LB',0,'R');

        if($src_tipe=='2')
        {
            $pdf->cell(24,6,number_format($grand_nilai_perforasi),'LBR',0,'R');    
        }
        
        $pdf->cell(($src_status=='1'?0:23.5),6,number_format($grand_retribusi),'LBR',($src_status!='1'?0:1),'R');
        
        if($src_status!='1'){
            $pdf->cell(0,6,'','BR',1);
        }

    }
    else{
        $widths = array(259.5);
        $pdf->setWidths($widths);
        $data = array(array('Data tidak tersedia!','C'));
        $pdf->row($data);
    }
    
    $pdf->setFont('Arial','',9);
    $pdf->cell(0,6,'Printed on '.date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIZATION_ACR." ".$_CITY);
    $pdf->Output();
?>