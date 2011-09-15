<?php
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	$datos = json_decode( str_replace("\\","",$_POST['datos']));
	require_once 'consolidadoPartido_inc.php';
	
	echo $numRows;
	
?>
