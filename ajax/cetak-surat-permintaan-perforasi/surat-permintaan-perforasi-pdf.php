<?php
	require_once("inc/init.php");
  	require_once("../../lib/global_obj.php");
  	require_once("../../lib/fpdf/MC_TABLE.php");	
	require_once("../../helpers/date_helper.php");
    
    $global = new global_obj($db);

    $id_skrd = $_GET['id'];

    $sql = "SELECT a.no_skrd,a.nm_rekening,b.kd_karcis,b.nilai_per_lembar,b.jumlah_blok,b.isi_per_blok,b.jumlah_lembar,b.tgl_permohonan,b.nm_pemohon,b.nip_pemohon
            FROM app_skrd as a INNER JOIN 
            (SELECT x.fk_skrd,x.kd_karcis,x.nilai_per_lembar,x.jumlah_blok,x.isi_per_blok,x.jumlah_lembar,x.tgl_permohonan,x.nm_pemohon,x.nip_pemohon
            FROM app_permohonan_karcis as x) as b ON(a.id_skrd=b.fk_skrd) WHERE a.id_skrd='".$id_skrd."'";
      
    $result = $db->Execute($sql);
    $n_skrd = $result->RecordCount();

    if($n_skrd>0)
    {
        $row = $result->FetchRow();   
        $system_params = $global->get_system_params();
    }

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
    $pdf->SetMargins(15,15,15);

    $pdf->AddPage();    
    
    if($n_skrd>0)
    {
        //header
        $pdf->cell(85,5,'','LT',0);
        $pdf->cell(125,5,'','LT',0);
        $pdf->cell(0,5,'','LTR',1);
        
        $pdf->setFont('Arial','B',11);
        $pdf->cell(85,4,'PEMERINTAH '.strtoupper($system_params[7]." ".$system_params[6]),'L',2,'C');
        $pdf->setFont('Arial','B',14);
        $pdf->cell(85,6,strtoupper($system_params[2]),'L',0,'C');
        
        $pdf->setFont('Arial','B',11);
        $pdf->setY(20);
        $pdf->setX(100);
        $pdf->cell(125,3,'','L',0);
        $pdf->cell(0,3,'','LR',1);

        $pdf->setX(100);
        $pdf->cell(125,4,'SURAT PERMINTAAN PERFORASI','L',0,'C');
        $pdf->cell(0,2,'','LR',2);
        $pdf->setFont('Arial','',11);
        $pdf->cell(0,5,'Tanggal :','LR',1);
        
        $pdf->setY(27);
        $pdf->setX(100);
        $pdf->cell(125,3,'','L',1);
        $pdf->setFont('Arial','',10);
        $pdf->cell(85,4,$system_params[3].", Telp. ".$system_params[4],'L',0,'C');
        $pdf->setFont('Arial','',11);
        $pdf->cell(40,4,'Kepada Yth. : ','L',0,'R');
        $pdf->cell(85,4,'KEPALA BADAN PENDAPATAN DAERAH','',0,'L');
        $pdf->cell(0,4,indo_date_format($row['tgl_permohonan'],'longDate'),'LR',1);

        $pdf->setFont('Arial','B',11);
        $pdf->cell(85,5,strtoupper($system_params[6]),'L',0,'C');
        $pdf->cell(40,5,'','L',0,'R');
        $pdf->setFont('Arial','',11);
        $pdf->cell(85,5,strtoupper($system_params[7])." ".strtoupper($system_params[6]),'',0,'L');
        $pdf->cell(0,5,'','LR',1);

        $pdf->cell(85,4,'','LB',0);
        $pdf->cell(125,4,'','LB',0);
        $pdf->cell(0,4,'','LBR',1);
        //end of header

        $pdf->cell(4,10,'','L');
        $pdf->cell(0,10,'Mohon agar dapat diperforasi sebagai berikut : ','R',1);

        $pdf->cell(10,5,'','LT');
        $pdf->cell(99,5,'','LT');
        $pdf->cell(20,5,'','LT');
        $pdf->cell(30,5,'','LT');
        $pdf->cell(0,8,'Banyaknya','LTRB',1,'C');

        $pdf->setY(58);
        $pdf->cell(10,5,'No.','L',0,'C');
        $pdf->cell(99,5,'Jenis dan Nomor Urut','L',0,'C');
        $pdf->cell(20,5,'Kode','L',0,'C');
        $pdf->cell(30,5,'Nilai Lembar','L',0,'C');
        
        $pdf->setY(61);
        $pdf->setX(174);
        $pdf->cell(30,2,'','L',0,'C');
        $pdf->cell(30,2,'','L',0,'C');
        $pdf->cell(30.5,2,'','LR',1,'C');

        $pdf->setY(63);

        $pdf->cell(10,3,'','L');
        $pdf->cell(99,3,'','L');
        $pdf->cell(20,3,'','L');
        $pdf->cell(30,3,'','L');

        $pdf->cell(30,3,'Jumlah Blok','L',0,'C');
        $pdf->cell(30,3,'Isi Blok','L',0,'C');
        $pdf->cell(30.5,3,'Jumlah Lembar','LR',1,'C');

        $pdf->cell(10,2,'','L');
        $pdf->cell(99,2,'','L');
        $pdf->cell(20,2,'','L');
        $pdf->cell(30,2,'','L');
        $pdf->cell(30,2,'','L');
        $pdf->cell(30,2,'','L');
        $pdf->cell(30.5,2,'','LR',1);

        //data content
        $widths = array(10,99,20,30,30,30,30.5);
        $pdf->setWidths($widths);
        $datas = array(array('1.','C',),
                       array(str_pad($row['nm_rekening']." - ".$row['no_skrd'],1120,' '),'L'),
                       array($row['kd_karcis'],'C'),
                       array(number_format($row['nilai_per_lembar']),'R'),
                       array(number_format($row['jumlah_blok']),'R'),
                       array(number_format($row['isi_per_blok']),'R'),
                       array(number_format($row['jumlah_lembar']),'R'));

        $pdf->Row($datas);
        
        $pdf->cell(83,8,'Disetujui oleh,','L',0,'C');
        $pdf->cell(83,8,'Diperiksa oleh,','',0,'C');
        $pdf->cell(83.5,8,'Pemohon,','R',1,'C');

        $pdf->cell(83,21,'','L');
        $pdf->cell(83,21,'');
        $pdf->cell(83.5,21,'','R',1);

        $pdf->setFont('Arial','UB',11);
        $pdf->cell(83,5,$system_params[25],'L',0,'C');
        $pdf->cell(83,5,$system_params[28],'',0,'C');
        $pdf->cell(83.5,5,$row['nm_pemohon'],'R',1,'C');

        $pdf->setFont('Arial','',11);
        $pdf->cell(83,5,'NIP. '.$system_params[26],'LB',0,'C');
        $pdf->cell(83,5,'NIP. '.$system_params[29],'B',0,'C');
        $pdf->cell(83.5,5,'NIP. '.$row['nip_pemohon'],'RB',1,'C');

    }else{
        $pdf->setFont('Arial','B',12);
        $pdf->SetTextColor(249,27,27);
        $pdf->cell(0,10,'data tidak ditemukan!','',0,'C');
    }
	
    $pdf->Output();
?>