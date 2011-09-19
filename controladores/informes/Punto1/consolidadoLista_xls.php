<?php
	session_start();
	date_default_timezone_set('America/Bogota');
	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=informe.xls");
	header('Cache-Control: max-age=0');
	
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoLista']);
	require_once 'consolidadoLista_inc.php';
	
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Consolidado Partido.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'UBICACION')
            ->setCellValue('B1', 'CORPORACION')
            ->setCellValue('C1', 'NOMBRE')
            ->setCellValue('D1', 'VOTOS');
	
	//Dar un color a las cabeceras en este caso particular
	
	//Agregando los valores al informe
	$cont = 2;
	while($row = ibase_fetch_object($result)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->DIVIPOL);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row->CORPORACION);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$row->DESCRIPCION);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,$row->VOTOS);
		$cont++;
	}
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	//Buscando metodo para definir el tamanyo manualmente
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');
?>