<?php
	require_once('configuracionOTR.php');
	require_once('resumenVotacionPartido_inc.php');
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Votacion Partido Municipio.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODPARTIDO')
            ->setCellValue('B1', 'DESCRIPCION')
            ->setCellValue('C1', 'VOTOS');
	
	$cont = 2;
	while($row = ibase_fetch_object($result)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODPARTIDO);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->DESCRIPCION));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,number_format($row->VOTOS));
		$cont++;
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	switch($_GET['formato']) {
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=votaPartMunicipio.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=votaPartMunicipio.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=votaPartMunicipio.txt");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
			$objWriter->setDelimiter(',');
			$objWriter->setEnclosure('');
			$objWriter->setLineEnding("\r\n");
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
	}
	
	
?>