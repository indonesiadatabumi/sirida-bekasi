<?php
	session_start();
	
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connect.php";
    include_once ($_DOCUMENT_ROOT."/classes/functions.php"); 
    include_once ($_DOCUMENT_ROOT."/classes/cipher.php"); 
	include_once ($_DOCUMENT_ROOT."/classes/PHPExcel/PHPExcel.php");
	
	$f = new functions();
	$cipher_obj = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$objPHPExcel = new PHPExcel();

	$SysID = $_SYSTEM_IDENTITY;
	
	$dec_key = "+^?:^&%*S!3!c!12!31T";

	$kd_tp = $f->get_KDTP();
	$nm_tp = $f->get_TP($kd_tp);
	$tgl1 = $cipher_obj->decrypt(urldecode($_GET['tgl1']),$dec_key);
	$tgl2 = $cipher_obj->decrypt(urldecode($_GET['tgl2']),$dec_key);	

	$objPHPExcel->getProperties()->setCreator($SysID['system_name_acr'])
			    ->setLastModifiedBy($SysID['system_name_acr'])
			    ->setTitle("Laporan Penerimaan Bank")
			    ->setSubject("Laporan Penerimaan Bank");
	$objPHPExcel->setActiveSheetIndex(0);
	$worksheet = $objPHPExcel->getActiveSheet();
	$worksheet->setTitle('Sheet1');

	//set column width	
	$worksheet->getColumnDimension('A')->setWidth(5);
	$worksheet->getColumnDimension('B')->setWidth(15);
	$worksheet->getColumnDimension('C')->setWidth(20);
	$worksheet->getColumnDimension('D')->setWidth(35);
	$worksheet->getColumnDimension('E')->setWidth(35);
	$worksheet->getColumnDimension('F')->setWidth(35);
	$worksheet->getColumnDimension('G')->setWidth(35);
	$worksheet->getColumnDimension('H')->setWidth(15);

	//mergecell
	$worksheet->mergeCells('A1:H1');
	$worksheet->mergeCells('A2:H2');

	//create the worksheet	
	$worksheet->setCellValue('A1', "Laporan Transaksi BPHTB di ".$nm_tp);
 	$worksheet->setCellValue('A2', "Per Tanggal : ".$f->gabung_duaTanggal($tgl1,$tgl2));

 	$fontStyle = array(
		 'font' => array(				 				 
				 'size' => 16,
				 'bold' => true
		 )
	);
	$worksheet->getStyle('A1')->applyFromArray($fontStyle);

 	$allignment['centertop'] = array();
	$allignment['centertop'] ['alignment']=array();
	$allignment['centertop'] ['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_CENTER;

	$allignment['righttop'] ['alignment']=array();
	$allignment['righttop'] ['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;

	$worksheet->getStyle ( 'A1' )->applyFromArray ($allignment['centertop']);
	$worksheet->getStyle ( 'A2' )->applyFromArray ($allignment['centertop']);

 	$worksheet->setCellValue('A4','No.');
 	$worksheet->setCellValue('B4','No Booking');
 	$worksheet->setCellValue('C4','NOP');
 	$worksheet->setCellValue('D4','Nama Penjual');
 	$worksheet->setCellValue('E4','Nama Pembeli');
 	$worksheet->setCellValue('F4','Alamat OP');
 	$worksheet->setCellValue('G4','PPAT/Notaris');
 	$worksheet->setCellValue('H4','Nilai BPHTB');

 	$fontStyle = array(
		 'font' => array(
				 'bold' => true
		 )
	);
	$worksheet->getStyle('A4:H4')->applyFromArray($fontStyle);

 		
	$worksheet->getStyle('A4:H4')->getFill()->applyFromArray(
			array(
			 'type' => PHPExcel_Style_Fill::FILL_SOLID,
			 'startcolor' => array('argb' => 'FFEEEEEE')
			 )
	);

	$thin = array ();
	$thin['borders']=array();
	$thin['borders']['allborders']=array();
	$thin['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THIN;
	$worksheet->getStyle ( 'A4:H4' )->applyFromArray ($thin);
			
	$worksheet->getStyle ( 'A4:H4' )->applyFromArray ($allignment['centertop']);
	
	$cond="";
	if(!empty($tgl1) and !empty($tgl2))
	{			
		$x2 = explode('-',$tgl2);
		$tgl2 = date('Y-m-d',mktime(0,0,0,$x2[1],$x2[2]+1,$x2[0]));
		$cond="WHERE a.tgl_pembayaran_bphtb between to_date('".$tgl1."','yyyy-mm-dd')  and to_date('".$tgl2."','yyyy-mm-dd')";
	}	

	$cond.=(!empty($cond)?" AND":"WHERE")." (a.kd_booking=b.kd_booking and a.kd_booking=b.kd_booking and a.kd_booking=c.kd_bphtb and a.kd_booking=d.kd_bphtb) and (a.kd_tp='".$kd_tp."')";

	$sql = "SELECT a.nop,a.kd_booking,c.nm_pembeli,d.nm_penjual,b.alamat_op,b.luas_tanah,b.luas_bng,a.bphtb_dibayar,b.reg_ppat
			FROM pembayaran_bphtb a,tbl_data_transaksi b,tbl_pembeli c,tbl_penjual d ".$cond;
					
	$result	= $db->Execute($sql);
	if (!$result) 
		die($db->ErrorMsg());

	$no = 0;
	$tot_bphtb = 0;
	$baris = 4;

	while($row = $result->FetchRow())
	{
		$no++;
		$baris++;
		$nop	 			= "'".$row['NOP'];
		$kd_booking			= "'".$row['KD_BOOKING'];
		$nm_pembeli			= $row['NM_PEMBELI'];						
		$nm_penjual			= $row['NM_PENJUAL'];						
		$alamat_op			= $row['ALAMAT_OP'];
		$luas_tanah			= $row['LUAS_TANAH'];
		$luas_bng			= $row['LUAS_BNG'];			
		$bphtb				= $row['BPHTB_DIBAYAR'];	    
	    $tot_bphtb += $bphtb;		
		$ppat = $f->convert_value(array('table'=>'tbl_user','cs'=>'fullname','cd'=>'register_id','vd'=>$row['REG_PPAT']));

		$worksheet->setCellValue('A'.$baris,$no);
 		$worksheet->setCellValue('B'.$baris,$kd_booking);
 		$worksheet->setCellValue('C'.$baris,$nop);
 		$worksheet->setCellValue('D'.$baris,$nm_pembeli);
 		$worksheet->setCellValue('E'.$baris,$nm_penjual);
 		$worksheet->setCellValue('F'.$baris,$alamat_op);
 		$worksheet->setCellValue('G'.$baris,$ppat);
 		$worksheet->setCellValue('H'.$baris,$bphtb);
 		
 		$worksheet->getStyle ( 'A'.$baris.':C'.$baris )->applyFromArray($allignment['centertop']);
	}
	
	$worksheet->getStyle ( 'A5:H'.$baris )->applyFromArray ($thin);

	$baris++;
	$worksheet->mergeCells('A'.($baris).':G'.($baris));
	$worksheet->setCellValue('A'.$baris,'Total');

	//set allignment to right
	$worksheet->getStyle('A'.$baris)->applyFromArray($allignment['righttop']);
	//set border
	$worksheet->getStyle('A'.$baris.':H'.$baris)->applyFromArray($thin);

	$fontStyle = array(
		 'font' => array(
				 'bold' => true
		 )
	);
	$worksheet->getStyle('A'.$baris.':H'.$baris)->applyFromArray($fontStyle);

	$worksheet->setCellValue('H'.$baris,$tot_bphtb);

 	$filename = "lap_penerimaan".date('YmdHis').".xls";
	header ( 'Content-Type: application/vnd.ms-excel' );  		
  	header ( 'Content-Disposition: attachment;filename="'.$filename.'"' ); 
  	header ( 'Cache-Control: max-age=0' );
  	$writer = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
  	$writer->save ( 'php://output' );	
?>
