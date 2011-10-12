<?php
	session_start();
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=consolidadoPartidoLista.doc");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoPartidoLista']);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	$partidos = array();
	$candidatos = array();
	
	while($row = ibase_fetch_object($result)) {
		array_push($partidos,$row);
	}
	if($result1 != null){
		while($row = ibase_fetch_object($result1)) {
			array_push($candidatos,$row);
		}
	}
	
	//Configurancion de las cabeceras
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
							 ->setLastModifiedBy("Ing. Luis A. Sanchez")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Consolidado Partido Lista")
							 ->setKeywords("office 2007 openxml")
							 ->setCategory("");
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'VOTOS');
	//Fin de las cabeceras
	
	
	//Configurado del contenido
	$cont = 2;
	foreach($partidos as $partido) {
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$partido->CODIGO);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($partido->DESCRIPCION));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$partido->VOTOS);
		//Hacer un cambio de color para las celdas de los partidos
		$cont++;
		
		foreach($candidatos as $candidato) {
			if($candidato->CODPARTIDO == $partido->CODIGO) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$partido->CODIGO.'-'.$candidato->CODCANDIDATO);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($candidato->DESCRIPCION));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$candidato->VOTOS);
				$cont++;
			}
		}
	}
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	//Fin del contenido
	
	
	//Cerrando la conexion
	ibase_free_result($result);
	if($result1 != null){ibase_free_result($result1);}
	ibase_close($firebird);
	
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	$objWriter->setSheetIndex(0);
	$objWriter->save('php://output');
	
?>