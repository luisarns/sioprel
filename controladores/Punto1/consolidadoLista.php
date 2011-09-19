<?php
	session_start();
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$_SESSION['consolidadoLista'] = serialize($datos);
	
	require_once 'consolidadoLista_inc.php';
	
	 $salida = array();
	while($row = ibase_fetch_object($result)){
		$ars = array(
		'divipol'=>$row->DIVIPOL
		,'corporacion'=>$row->CORPORACION
		,'descripcion'=>htmlentities($row->DESCRIPCION)
		,'votos'=>$row->VOTOS
		);
		array_push($salida,$ars);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($salida);
?>
