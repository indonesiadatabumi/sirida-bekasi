<?php
    require_once("inc/init.php");
    require_once("../../lib/global_obj.php");
    require_once("../../lib/fpdf/MC_TABLE.php");
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

    $pdf = new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,10,10);

    $pdf->AddPage();    
    
    if($n_ba>0)
    {
        //header
        $src = "../../img/logo_pemkot_bekasi.png";
        $w = 16;
        $x = $pdf->GetX()+1;
        $y = $pdf->GetY()+2;
        $pdf->Image($src,$x,$y,$w);

        $pdf->setFont('Arial','B',11);
        $pdf->cell(17,3,'','LT');
        $pdf->cell(65,3,'','T',0);
        $pdf->cell(93,3,'','LT',0);
        $pdf->cell(0,3,'','LTR',1);

        $pdf->cell(17,4,'','L');
        $pdf->cell(65,4,"PEMERINTAH ".strtoupper($system_params[7]." ".$system_params[6]),'',0,'C');
        $pdf->cell(93,4,'','L',0);
        $pdf->cell(0,4,'','LR',1);

        $pdf->cell(17,4,'','L');
        $pdf->cell(65,4,strtoupper($system_params[2]),'',0,'C');
        $pdf->cell(93,4,'BERITA ACARA','L',0,'C');
        $pdf->setFont('Arial','',11);
        $pdf->cell(0,4,'No. :','LR',1,'C');

        $pdf->setFont('Arial','B',11);
        $pdf->cell(17,4,'','L');
        $pdf->setFont('Arial','',10);
        $pdf->cell(65,4,$system_params[3],'',0,'C');
        $pdf->setFont('Arial','B',11);
        $pdf->cell(93,4,'SERAH TERIMA BENDA BERHARGA','L',0,'C');
        $pdf->cell(0,4,$row['no_berita_acara'],'LR',1,'C');
        
        $pdf->cell(17,4,'','L');
        $pdf->cell(65,4,strtoupper($system_params[6]),'',0,'C');
        $pdf->cell(93,4,'','L',0,'C');        
        $pdf->cell(0,4,'','LR',1,'C');        

        $pdf->cell(17,3,'','LB');
        $pdf->cell(65,3,'','B',0);
        $pdf->cell(93,3,'','LB',0);
        $pdf->cell(0,3,'','BLR',1);

        $pdf->cell(0,4,'','LR',1);    

        $pdf->cell(4,5,'','L');
        $pdf->setFont('Arial','',11);
        $pdf->cell(0,5,"Pada hari ini ".$hari." tanggal ".$tgl." bulan ".$bln." tahun ".$thn.", Kami yang bertanda tangan di bawah ini :",'R',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(6,5,'1.','',0);
        $pdf->cell(20,5,'Nama');
        $pdf->cell(0,5,': '.$row['nm_pihak_kesatu'],'R',1);

        $pdf->cell(10,5,'','L');
        $pdf->cell(20,5,'NIP');
        $pdf->cell(0,5,': '.$row['nip_pihak_kesatu'],'R',1);

        $pdf->cell(10,5,'','L');
        $pdf->cell(20,5,'Jabatan');
        $pdf->cell(0,5,': '.$row['jbt_pihak_kesatu'],'R',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(0,5,'Selanjutnya disebut sebagai PIHAK KESATU','R',1);

        $pdf->cell(0,4,'','LR',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(6,5,'2.','',0);
        $pdf->cell(20,5,'Nama');
        $pdf->cell(0,5,': '.$row['nm_pihak_kedua'],'R',1);

        $pdf->cell(10,5,'','L');
        $pdf->cell(20,5,'NIP');
        $pdf->cell(0,5,': '.$row['nip_pihak_kedua'],'R',1);

        $pdf->cell(10,5,'','L');
        $pdf->cell(20,5,'Jabatan');
        $pdf->cell(0,5,': '.$row['jbt_pihak_kedua'],'R',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(0,5,'Selanjutnya disebut sebagai PIHAK KEDUA','R',1);

        $pdf->cell(0,4,'','LR',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(0,5,'PIHAK KESATU telah menyerahkan Benda Berharga berdasarkan Surat Permohonan Perforasi Nomor ','R',1);
        
        $pdf->cell(4,5,'','L');
        $pdf->cell(0,5,$row['no_surat_permohonan'].' tanggal '.indo_date_format($row['tgl_surat_permohonan'],'longDate').' kepada PIHAK KEDUA','R',1);

        $pdf->cell(4,5,'','L');
        $pdf->cell(0,5,'Adapun Benda Berharga yang DISERAHTERIMAKAN sebagai berikut :','R',1);

        $pdf->cell(0,4,'','LR',1);

        $pdf->setFont('Arial','',10);
        $pdf->cell(10,3,'','LT');
        $pdf->cell(50,3,'','LT');
        $pdf->cell(24,4,'','LT');
        $pdf->cell(24,4,'','LT');
        $pdf->cell(0,6,'Jumlah yang Diterima','LTRB',1,'C');

        $pdf->setY(111);
        $pdf->Cell(10,10,'No.','L',0,'C');
        $pdf->Cell(50,10,'Nama Benda Berharga','L',0,'C');

        $pdf->setY(112);
        $pdf->setX(70);
        $pdf->Cell(24,4,'Kode Benda','L',0,'C');
        $pdf->Cell(24,4,'Nilai Per','L',1,'C');        

        $pdf->setY(116);
        $pdf->setX(70);

        $pdf->Cell(24,4,'Berharga','L',0,'C');
        $pdf->Cell(24,4,'Lembar','L',0,'C');

        $pdf->setY(114);
        $pdf->setX(118);

        $pdf->Cell(20,1,'','L');
        $pdf->Cell(26,1,'','L',0,'C');
        $pdf->Cell(21,1,'','L');
        $pdf->Cell(21,1,'','LR',1);
    
        $pdf->setY(115);
        $pdf->setX(118);

        $pdf->Cell(20,4,'Jumlah','L',0,'C');
        $pdf->Cell(26,4,'Jumlah Lembar','L',0,'C');
        $pdf->Cell(21,4,'Jumlah','L',0,'C');
        $pdf->Cell(21,4,'Nomor','LR',1,'C');

        $pdf->setY(121);
        $pdf->cell(10,3,'','L');
        $pdf->cell(50,3,'','L');

        $pdf->setY(120);
        $pdf->setX(70);
        
        $pdf->Cell(24,4,'','L');
        $pdf->Cell(24,4,'','L');

        $pdf->setY(119);
        $pdf->setX(118);

        $pdf->Cell(20,4,'Blok','L',0,'C');
        $pdf->Cell(26,4,'Per Blok','L',0,'C');
        $pdf->Cell(21,4,'Lembar','L',0,'C');
        $pdf->Cell(21,4,'Seri','LR',1,'C');

        $pdf->setY(123);
        $pdf->setX(118);

        $pdf->Cell(20,1,'','L');
        $pdf->Cell(26,1,'','L');
        $pdf->Cell(21,1,'','L');
        $pdf->Cell(21,1,'','LR');
        
        $pdf->setY(124);
        //data content
        $widths = array(10,50,24,24,20,26,21,21);
        $pdf->setWidths($widths);


        $sql = "SELECT b.nm_rekening,b.kd_karcis,b.nilai_per_lembar,b.jumlah_blok,b.isi_per_blok,b.jumlah_lembar,
                b.no_seri FROM app_dtl_ba_stbb as a LEFT JOIN app_permohonan_karcis as b ON (a.fk_permohonan=b.id_permohonan) 
                WHERE a.fk_berita_acara='".$row['id_berita_acara']."'";
        
        $result = $db->Execute($sql);

        $no = 0;
        while($row2=$result->FetchRow())
        {
            $no++;
            $datas = array(array($no.'.','C',),
                           array("Karcis ".$row2['nm_rekening'],'L'),
                           array($row2['kd_karcis'],'C'),
                           array(number_format($row2['nilai_per_lembar'],0,',','.'),'R'),
                           array(number_format($row2['jumlah_blok']),'R'),
                           array(number_format($row2['isi_per_blok']),'R'),
                           array(number_format($row2['jumlah_lembar']),'R'),
                           array($row2['no_seri'],'C')
                           );

            $pdf->Row($datas);
        }

        $pdf->cell(4,10,'','L');
        $pdf->cell(0,10,'Demikian Berita Acara Serah Terima Benda Berharga ini dibuat menurut sebenarnya untuk dipergunakan seperlunya.','R',1);

        $pdf->cell(0,2,'','LR',1);

        $pdf->cell(97.9,4,'Yang Menerima,','L',0,'C');
        $pdf->cell(0,4,'Yang Menyerahkan,','R',1,'C');
        $pdf->cell(97.9,4,'PIHAK KEDUA,','L',0,'C');
        $pdf->cell(0,4,'PIHAK KESATU,','R',1,'C');

        $pdf->cell(0,20,'','LR',1);

        $pdf->setFont('Arial','UB',11);
        $pdf->cell(97.9,8,$row['nm_pihak_kedua'],'LB',0,'C');
        $pdf->cell(0,8,$row['nm_pihak_kesatu'],'RB',1,'C');

        $pdf->setFont('Arial','',11);
        $pdf->cell(0,6,'MODEL : DPD-56');

    }else{
        $pdf->setFont('Arial','B',12);
        $pdf->SetTextColor(249,27,27);
        $pdf->cell(0,10,'data tidak ditemukan!','',0,'C');
    }
    
    $pdf->Output();
?>