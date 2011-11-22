<?php
	require_once('configuracionOTR.php');
	require_once('listadoVotacionCandidato_inc.php');
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Listado Votacion Candidato.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', utf8_encode($nomCorporacion));
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', utf8_encode($nomDivipol));
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'CODIGO')
            ->setCellValue('B3', 'NOMBRES')
            ->setCellValue('C3', 'APELLIDOS')
            ->setCellValue('D3', 'PARTIDO')
            ->setCellValue('E3', 'VOTOS');
						 
	
	$cont = 4;
        if (isset($result)) {
            foreach ($result as $row) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT) . '-' . str_pad($row['codcandidato'], 3, '0', STR_PAD_LEFT));
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row['nombres']));
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,utf8_encode($row['apellidos']));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,utf8_encode($row['descripcion']));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$cont,number_format($row['votos']));
                    $cont++;
            }
        }
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
	
	switch($_GET['formato']){
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=listadoVotacionCandidato.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=listadoVotacionCandidato.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=listadoVotacionCandidato.txt");
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