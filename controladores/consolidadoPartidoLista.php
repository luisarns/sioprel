<?php 
	session_start(); 
	header("Content-Type: text/plain");	
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$jsonStr = $_POST['datos'];
	$datos = json_decode(str_replace("\\","",$jsonStr));
	$_SESSION['consolidadoPartidoLista'] = serialize($datos);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	$arrConPar = array();
	while($row = ibase_fetch_object($result)){
		$conspart = array();
		$conspart['codigo']  = $row->CODIGO;
		$conspart['partido'] = htmlentities($row->DESCRIPCION);
		$conspart['votos']   = $row->VOTOS;
		array_push($arrConPar,$conspart);
	}
	
	//Guardar los registros de los partidos en un temporal al igual que los de los candidatos
	//cuando el temporal contenga registro de los candidatos
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	echo json_encode($arrConPar);
	
?>
