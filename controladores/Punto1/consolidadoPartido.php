<?php
	session_start();
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$_SESSION['consolidadoPartido'] = serialize($datos);
	
	
	require_once 'consolidadoPartido_inc.php';
	
	$salida = array();
	while($row = ibase_fetch_object($result)){
		$ars = array(
		'divipol'=>$row->DIVIPOL
		,'codigo'=>$row->CODIGO
		,'partido'=>$row->PARTIDO
		,'votos'=>$row->VOTOS
		);
		array_push($salida,$ars);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($salida);
?>
