<?php
	session_start();
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$_SESSION['elegidosAsignacionCurules'] = serialize($datos);
	
	require_once 'elegidosAsignacionCurules_inc.php';
	
	
	$arrCandVota = array();
	// while($row = ibase_fetch_object($result)){
		// $votaCand = array();
		// $votaCand['codigo']    = $row->CODIGO;
		// $votaCand['nombres']   = htmlentities($row->NOMBRES);
		// $votaCand['apellidos'] = htmlentities($row->APELLIDOS);
		// $votaCand['partido']   = htmlentities($row->DESCRIPCION);
		// $votaCand['votos']     = $row->VOTOS;
		// array_push($arrCandVota,$votaCand);
	// }
	
	// ibase_free_result($result);
	// ibase_close($firebird);
	echo json_encode($arrCandVota);
?>