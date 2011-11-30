<?php
	require_once('configuracionOTR.php');
	require_once('listElegidoCorporacion_inc.php');
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Listado Elegidos Corporacion.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $nomCorporacion);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $nmDepartamento.' '.$nmMunicipio .' '. $nmZona.''.$nmComuna);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $nmPueto);
        $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
        
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'CODIGO')
            ->setCellValue('B4', 'NOMBRES')
            ->setCellValue('C4', 'APELLIDOS')
            ->setCellValue('D4', 'PARTIDO')
            ->setCellValue('E4', 'VOTOS');
						 
	
	$cont = 5;
        if (isset($result)) {
            foreach ($result as $row) {
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,utf8_encode(str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT) . '-' . str_pad($row['codcandidato'], 3, '0', STR_PAD_LEFT)));	
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row['nombres']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$row['apellidos']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,$row['descripcion']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$cont,number_format($row['votos']));
                $cont++;
            }
        }
	
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        
	
	switch($_GET['formato']){
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=listadoElegidosCorporacion.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=listadoElegidosCorporacion.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=listadoElegidosCorporacion.txt");
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