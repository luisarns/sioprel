<?php
	session_start();
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$_SESSION['consolidadoCorporacion'] = serialize($datos);
	
	
	require_once 'consolidadoCorporacion_inc.php';
	
	$salida = array();
	while($row = ibase_fetch_object($result)){
		$ars = array(
		'divipol'=>$row->DIVIPOL
		,'codigo'=>$row->CODIGO
		,'descripcion'=>htmlentities($row->DESCRIPCION)
		,'tipovoto'=>$row->TIPOVOTOS
		,'votos'=>$row->VOTOS
		);
		array_push($salida,$ars);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($salida);
?>
