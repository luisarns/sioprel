<?php
	require_once('configuracionOTR.php');
	require_once('listasMayorVotacion_inc.php');
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Listas Mayor Votacion.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $nomCorporacion);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $nomDivipol);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'CODIGO')
            ->setCellValue('B3', 'LISTA')
            ->setCellValue('C3', 'VOTOS');
				
	$cont = 4;
        if (isset ($result)) {
            foreach($result as $row) {
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT));
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row['descripcion']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,number_format($row['votos']));
                $cont++;
            }
        }
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	
	
	switch($_GET['formato']) {
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=listadoListas.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=listadoListas.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=listadoListas.txt");
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