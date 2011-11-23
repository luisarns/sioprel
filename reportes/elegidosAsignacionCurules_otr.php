<?php
	require_once('configuracionOTR.php');
	require_once('elegidosAsignacionCurules_inc.php');
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                             ->setLastModifiedBy("Ing. Luis A. Sanchez")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Elegidos Asignacion Curules")
                             ->setKeywords("office 2007 openxml")
                             ->setCategory("");
        
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', utf8_encode($nomCorporacion));
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', utf8_encode($nomDivipol));
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        
        
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', utf8_encode('No.Curules'))
            ->setCellValue('B3', $nocurules)
            ->setCellValue('A4', 'Cociente')
            ->setCellValue('B4', $cuociente)
            ->setCellValue('C3', 'Cifra Repartidora')
            ->setCellValue('D3', $cifrarepartidora);
        
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', 'CODIGO')
            ->setCellValue('B5', 'NOMBRES')
            ->setCellValue('C5', 'APELLIDOS')
            ->setCellValue('D5', 'PARTIDO')
            ->setCellValue('E5', 'VOTOS');
        
	//Configurado del contenido
	$cont = 6;
	foreach ($result as $row) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($row['codpartido'],3,'0',STR_PAD_LEFT) . '-' . str_pad($row['codcandidato'],3,'0',STR_PAD_LEFT));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($row['nombres']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($row['apellidos']));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, utf8_encode($row['descripcion']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$cont, number_format($row['votos']));
            $cont++;
	}
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	//Fin del contenido

	
	//Creando el escritor en funcion del formato al que se quiera exportar el documento
	switch($_GET['formato']) {
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=elegidosAsignacionCurules.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=elegidosAsignacionCurules.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=elegidosAsignacionCurules.txt");
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