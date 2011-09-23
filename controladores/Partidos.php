<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query ="SELECT codpartido as id , descripcion as nombre FROM ppartidos ORDER BY codpartido";
	
	$result   = ibase_query($firebird,$query);
	
	$partidos = array();
	while($row = ibase_fetch_object($result)){
		$partido = array();
		$partido['id'] = $row->ID;
		$partido['nombre'] = htmlentities($row->NOMBRE);
		array_push($partidos,$partido);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	echo json_encode($partidos);
?>