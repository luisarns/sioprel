<?php 
	session_start(); 
	header("Content-Type: text/plain");	
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$jsonStr = $_POST['datos'];
	$datos = json_decode(str_replace("\\","",$jsonStr));
	$_SESSION['consolidadoPartidoLista'] = serialize($datos);
	
	require_once('consolidadoPartidoLista_inc.php');
	
	$partidos = array();
	$candidatos = array();
	
	//Guardo temporalmente los datos
	while($row = ibase_fetch_object($result)) {
		array_push($partidos,$row);
	}
	if($result1 != null){
		while($row = ibase_fetch_object($result1)) {
			array_push($candidatos,$row);
		}
	}
	
	$arrConPar = array();
	foreach($partidos as $partido) {
		
		$conspart = array();
		$conspart['codigo']  = $partido->CODIGO;
		$conspart['partido'] = htmlentities($partido->DESCRIPCION);
		$conspart['votos']   = $partido->VOTOS;
		array_push($arrConPar,$conspart);
		
		foreach($candidatos as $candidato) {
			
			if($candidato->CODPARTIDO == $partido->CODIGO) {
				$cand = array();
				$cand['codigo']  = $partido->CODIGO.'-'.$candidato->CODCANDIDATO;
				$cand['partido'] = htmlentities($candidato->DESCRIPCION);
				$cand['votos']   = $candidato->VOTOS;
				array_push($arrConPar,$cand);
			}

		}
		
	}
	
	ibase_free_result($result);
	if($result1 != null){ibase_free_result($result1);}
	ibase_close($firebird);
	
	echo json_encode($arrConPar);
	
?>
