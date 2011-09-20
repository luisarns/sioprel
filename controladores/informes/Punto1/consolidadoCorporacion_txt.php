<?php
	session_start();
	date_default_timezone_set('America/Bogota');
	
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=consolidadoCorporacion.txt");
	header('Cache-Control: max-age=0');
	
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoCorporacion']);
	require_once 'consolidadoCorporacion_inc.php';
	
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Consolidado Corporacion.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'UBICACION')
            ->setCellValue('B1', 'CODIGO')
            ->setCellValue('C1', 'NOMBRE')
			->setCellValue('D1', 'TIPOVOTO')
            ->setCellValue('E1', 'VOTOS');
			
	//Agregando los valores al informe
	$cont = 2;
	while($row = ibase_fetch_object($result)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->DIVIPOL);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row->CODIGO);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$row->DESCRIPCION);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,$row->TIPOVOTOS);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$cont,$row->VOTOS);
		$cont++;
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	
	//Asignado las propiedades del archivo csv
	$objWriter->setDelimiter(',');
	$objWriter->setEnclosure('');
	$objWriter->setLineEnding("\r\n");
	$objWriter->setSheetIndex(0);

	$objWriter->save('php://output');
?>