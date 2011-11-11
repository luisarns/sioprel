<?php
	require_once('configuracionOTR.php');
	require_once('consolidadoPartidoLista_inc.php');
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                             ->setLastModifiedBy("Ing. Luis A. Sanchez")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Consolidado Partido Lista")
                             ->setKeywords("office 2007 openxml")
                             ->setCategory("");
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', utf8_encode('Participación'))
            ->setCellValue('B2', $participacion . '%')
            ->setCellValue('A3', utf8_encode('Abstención'))
            ->setCellValue('B3', $asbtencion . '%')
            ->setCellValue('C2', utf8_encode('Potencial'))
            ->setCellValue('D2', number_format($potencial))
            ->setCellValue('C3', utf8_encode('Corporación'))
            ->setCellValue('D3', $nomCorporacion);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', utf8_encode($nomDivipol));
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', utf8_encode('CÓDIGO'))
            ->setCellValue('B4', 'NOMBRE')
            ->setCellValue('C4', 'VOTOS')
            ->setCellValue('D4', utf8_encode('PARTICIPACIÓN'));
        
	//Configurado del contenido
	$cont = 5;
	foreach($partidos as $partido) {
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($partido->CODIGO, 3, '0', STR_PAD_LEFT));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($partido->DESCRIPCION));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($partido->VOTOS));
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, round($partido->VOTOS*100/$potencial,2) . '%');
		$cont++;
		
		foreach($candidatos as $candidato) {
			if($candidato->CODPARTIDO == $partido->CODIGO) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($partido->CODIGO, 3, '0', STR_PAD_LEFT) . '-' . str_pad($candidato->CODCANDIDATO, 3, '0', STR_PAD_LEFT));
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($candidato->DESCRIPCION));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($candidato->VOTOS));
				$cont++;
			}
		}
	}
        foreach ($votacionEspecial as $votoEsp) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,'');
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($votoEsp->DESCRIPCION));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($votoEsp->VOTOS));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, round($votoEsp->VOTOS*100/$potencial,2) . '%');
            $cont++;
        }
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	//Fin del contenido

	
	//Creando el escritor en funcion del formato al que se quiera exportar el documento
	switch($_GET['formato']) {
		case 'xls':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=consolidadoPartidoLista.xls");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		break;
		case 'doc':
			header("Content-type: application/msword");
			header("Content-Disposition: attachment; filename=consolidadoPartidoLista.doc");
			header('Cache-Control: max-age=0');
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->setSheetIndex(0);
			$objWriter->save('php://output');
		break;
		case 'txt':
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=consolidadoPartidoLista.txt");
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