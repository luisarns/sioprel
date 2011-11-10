<?php
	require_once('configuracionOTR.php');
	require_once('consolidadoPartidoLista_inc.php');
	
	//Configuracion para la generacion del pdf
//	$partidos = array();
//	$candidatos = array();
//	
//	while($row = ibase_fetch_object($result)) {
//		array_push($partidos,$row);
//	}
//	
//	if($result1 != null) {
//		while($row = ibase_fetch_object($result1)) {
//			array_push($candidatos,$row);
//		}
//	}
	
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
            ->setCellValue('A3', utf8_encode('CÓDIGO'))
            ->setCellValue('B3', 'NOMBRE')
            ->setCellValue('C3', 'VOTOS')
            ->setCellValue('D3', utf8_encode('PARTICIPACIÓN'));
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode('Participación'))
            ->setCellValue('B1', $participacion . '%')
            ->setCellValue('A2', utf8_encode('Abstención'))
            ->setCellValue('B2', $asbtencion . '%');


	//Configurado del contenido
	$cont = 4;
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
	
	//Cerrando la conexion
//	ibase_free_result($result);
//	if($result1 != null){ibase_free_result($result1);}
//	ibase_close($firebird);
	
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