<?php
	session_start();
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=consolidadoPartidoLista.doc");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoPartidoLista']);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	
	// //Creando el escritor
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	// $objWriter->setSheetIndex(0);
	// $objWriter->save('php://output');
?>