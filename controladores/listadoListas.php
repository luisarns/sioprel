<?php 
	session_start();	
	header("Content-Type: text/plain");	
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	$_SESSION['listadoListas'] = serialize($datos);
	// require_once 'listadoListas_inc.php';
	
	
	$arrListListas = array();
	// while($row = ibase_fetch_object($result)){
		// $listLista = array();
		// $listLista['codigo']    = $row->CODIGO;
		// $listLista['nombres']   = htmlentities($row->NOMBRES);
		// $listLista['apellidos'] = htmlentities($row->APELLIDOS);
		// $listLista['partido']   = htmlentities($row->DESCRIPCION);
		// $listLista['votos']     = $row->VOTOS;
		// array_push($arrListListas,$listLista);
	// }
	
	// ibase_free_result($result);
	// ibase_close($firebird);
	
	echo json_encode($datos);
?>