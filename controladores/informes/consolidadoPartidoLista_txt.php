<?php
	session_start();
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=consolidadoPartidoLista.txt");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoPartidoLista']);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	
	// //Creando el escritor
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	// //Asignado las propiedades del archivo csv
	// $objWriter->setDelimiter(',');
	// $objWriter->setEnclosure('');
	// $objWriter->setLineEnding("\r\n");
	// $objWriter->setSheetIndex(0);
	// $objWriter->save('php://output');
?>