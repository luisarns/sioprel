<?php
	session_start();
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=consolidadoPartidoLista.xls");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['consolidadoPartidoLista']);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	
	// //Creando el escritor
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	// $objWriter->save('php://output');
?>