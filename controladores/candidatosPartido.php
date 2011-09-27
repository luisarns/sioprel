<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$codpartido = $_POST['codpartido'];
	$estado = "INSCRITO";
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$query    =<<<EOF
	SELECT codpartido||'-'||codcandidato as codigo, nombres, apellidos, '$estado' estado 
	FROM pcandidatos 
	WHERE codpartido = $codpartido AND codcandidato <> 0
	ORDER BY codpartido,codcandidato
EOF;
	$result   = ibase_query($firebird,$query);
	
	$arrCandidatos = array();
	while($row = ibase_fetch_object($result)){
		$candidato = array();
		$candidato['codigo'] = $row->CODIGO;
		$candidato['nombres'] = htmlentities($row->NOMBRES);
		$candidato['apellidos'] = htmlentities($row->APELLIDOS);
		$candidato['estado'] = $row->ESTADO;
		array_push($arrCandidatos,$candidato);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($arrCandidatos);

?>