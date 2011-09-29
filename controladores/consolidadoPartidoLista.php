<?php 
	session_start(); 
	header("Content-Type: text/plain");	
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$jsonStr = $_POST['datos'];
	$datos = json_decode(str_replace("\\","",$jsonStr));
	$_SESSION['consolidadoPartidoLista'] = serialize($datos);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	$arConParLis = array();
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
	
	echo json_encode($arConParLis);
	
?>
