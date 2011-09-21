<?php
	session_start();
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=votaPartMunicipio.xls");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['votaPartMunicipio']);
	
	require_once 'votaPartMunicipio_inc.php';
	
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
			
	//Agregando los valores al informe
	$cont = 2;
	while($row = ibase_fetch_object($result)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODPARTIDO);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->DESCRIPCION));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$row->VOTOS);
		$cont++;
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>