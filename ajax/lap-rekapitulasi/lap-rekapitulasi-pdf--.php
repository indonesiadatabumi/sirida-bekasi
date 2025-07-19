<?php
	require_once("inc/init.php");
	require_once("../../lib/fpdf/MC_TABLE.php");
	require_once("../../helpers/date_helper.php");

	$kd_rekening = $_GET['rek'];
    $bln_retribusi = $_GET['bln_r'];
    $thn_retribusi = $_GET['thn_r'];
    $tipe_retribusi = $_GET['tip_r'];
    $status_bayar = $_GET['s_byr'];

    switch($status_bayar)
    {
        case '2':$skrd_cond = "WHERE(status_bayar='1')";break;
        case '3';$skrd_cond = "WHERE(status_bayar='0')";break;
        default:$skrd_cond = "";break;
    }

    $acc_condition = ($kd_rekening!=''?"(a.kd_rekening='".$kd_rekening."') AND":"");

    if($tipe_retribusi=='1')
    {
        $list_sql = "SELECT a.bln_retribusi,a.thn_retribusi,a.total_retribusi,b.* FROM app_nota_perhitungan as a 
                     INNER JOIN 
                        (SELECT x.id_skrd,x.no_skrd,to_char(x.tgl_skrd,'DD-MM-YYYY') as tgl_skrd,x.wp_wr_nama,x.wp_wr_alamat,y.* FROM app_skrd as x 
                            LEFT JOIN (SELECT ntpd,to_char(tgl_pembayaran,'DD-MM-YYYY') as tgl_pembayaran,denda,total_bayar,kd_billing FROM app_pembayaran_retribusi) as y ON (x.kd_billing=y.kd_billing)
                         ".$skrd_cond.") as b 
                     ON (a.fk_skrd=b.id_skrd)
                     WHERE ".$acc_condition." (a.bln_retribusi='".$bln_retribusi."') AND (a.thn_retribusi='".$thn_retribusi."')";
    }
    else
    {
        $list_sql = "SELECT 
                     (SELECT to_char(tgl_pengembalian,'DD-MM-YYYY') as tgl_pengembalian FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan) ORDER BY id_pengembalian ASC LIMIT 1)  as tgl_pengembalian,
                     (SELECT SUM(x.jumlah_lembar_kembali) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan))  as jumlah_lembar_kembali,
                     b.* FROM app_permohonan_karcis as a 
                     INNER JOIN 
                        (SELECT x.id_skrd,x.no_skrd,x.bln_retribusi,x.thn_retribusi,x.wp_wr_nama,x.wp_wr_alamat,y.* FROM app_skrd as x 
                         LEFT JOIN (SELECT ntpd,denda,total_bayar,kd_billing FROM app_pembayaran_retribusi) as y ON (x.kd_billing=y.kd_billing)
                         ".$skrd_cond.") as b 
                    ON (a.fk_skrd=b.id_skrd)
                    WHERE ".$acc_condition." (b.bln_retribusi='".$bln_retribusi."') AND (b.thn_retribusi='".$thn_retribusi."')";
    }
            
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

    $sql = "SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')";
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

    $pdf = new PDF('L','mm','Legal');
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,10,25);

    $pdf->AddPage();

    $pdf->setFont('Arial','B',14);
    $pdf->cell(0,5,"REKAPITULASI RETRIBUSI ".$jenis_retribusi,'',1,'C');
    $pdf->setFont('Arial','',12);
    $pdf->cell(0,5,'BULAN '.strtoupper(get_monthName($bln_retribusi))." TA. ".$thn_retribusi,'',1,'C');

    $pdf->ln(5);
    //header

    $pdf->SetFillColor(204,204,204);
    $pdf->setFont('Arial','',8);

    $pdf->cell(11,3.2,'','LT',0,'L',1);

    $pdf->cell(72,6,'Surat Ketetapan Retribusi Daerah','LT',0,'C',1);
    $pdf->cell(95,6,'Wajib Retribusi','LT',0,'C',1);
    $pdf->cell(102,6,'SSRD','LT',0,'C',1);
    //$pdf->cell(20,3,'','LT',0,'C',1);
    $pdf->cell(25,3,'','LRT',1,'C',1);

    $pdf->setY(28);
    $pdf->cell(11,6.2,'No.','L',0,'C',1);
    $pdf->setY(31);
    $pdf->setX(21);
    $pdf->cell(14,6,'No.SKRD','LT',0,'C',1);
    $pdf->cell(18,6,'Tgl.','LT',0,'C',1);
    $pdf->cell(20,6,'Jumlah','LT',0,'C',1);
    $pdf->cell(20,6,'Masa','LT',0,'C',1);

    $pdf->cell(45,6,'Nama','LT',0,'C',1);
    $pdf->cell(50,6,'Alamat','LT',0,'C',1);

    $pdf->cell(24,6,'NTPD','LT',0,'C',1);
    $pdf->cell(18,6,'Tgl. Setor','LT',0,'C',1);
    $pdf->cell(20,6,'Jumlah','LT',0,'C',1);
    $pdf->cell(20,6,'Denda','LT',0,'C',1);
	$pdf->cell(20,6,'Total Bayar','LT',0,'C',1);
	
    $pdf->setY(28);
    $pdf->setX(290);
    //$pdf->cell(20,6,'LRA','L',0,'C',1);
    $pdf->cell(25,6.3,'Tunggakan','LR',1,'C',1);

    $pdf->cell(11,3,'','L',0,'C',1);
    $pdf->setX(290);
    $pdf->cell(25,3.2,'','LR',0,'C',1);
   // $pdf->cell(25,3,'','LR',0,'C',1);

	$pdf->setY(37);

    $pdf->setFont('Arial','',8);
	
    if($list_of_data->RecordCount()>0)
	{
		$widths = array(11,14,18,20,20,45,50,24,18,20,20,20,25);
	    $pdf->setWidths($widths);

	    $no = 0;		
		while($row=$list_of_data->FetchRow())
		{
			$no++;
			foreach($row as $key => $val){
		        $key=strtolower($key);
		        $$key=$val;
		    }
		    $datas = array(array($no,'C'),
		    			   array($no_skrd,'C'),
		    			   array($tgl_skrd,'C'),
		    			   array(number_format($total_retribusi),'C'),
		    			   array(get_monthName($bln_retribusi)." ".$thn_retribusi,'C'),
		    			   array($wp_wr_nama,'L'),
		    			   array($wp_wr_alamat,'L'),
		    			   array($ntpd,'C'),
                           array($tgl_pembayaran,'C'),
                           array(number_format($total_bayar),'R'),
                           array(number_format($denda),'R'),
                           array(number_format($total_bayar+$denda),'R'),
                           array(number_format($total_bayar+$denda-$total_retribusi),'R'),
		    			   );
		    $pdf->row($datas);		    
		}		
	}
	else{
		$widths = array(259.5);
	    $pdf->setWidths($widths);
	    $data = array(array('Data tidak tersedia!','C'));
	    $pdf->row($data);
	}
	
	$pdf->setFont('Arial','',5);
	$pdf->cell(0,6,'Printed on '.date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIZATION_ACR." ".$_CITY);
    $pdf->Output();
?>