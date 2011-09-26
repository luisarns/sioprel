<?php 
	session_start();	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=listadoListas.xls");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$datos = unserialize($_SESSION['listadoListas']);
	
	require_once 'listadoListas_inc.php';
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Listas Mayor Votacion.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'PARTIDO')
            ->setCellValue('B1', 'VOTOS')
            ->setCellValue('C1', '(%)');
			
	//Agregando los valores al informe
	$cont = 2;
	// while($row = ibase_fetch_object($result)) {
		// $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,utf8_encode($row->PARTIDO));
		// $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row->VOTOS);
		// $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,$row->PORCENTAJE);
		// $cont++;
	// }

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
	// ibase_free_result($result);
	// ibase_close($firebird);
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>