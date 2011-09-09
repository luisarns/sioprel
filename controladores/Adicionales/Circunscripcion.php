<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query ='SELECT codcircunscripcion as id , descripcion as nombre FROM pcircunscripcion ORDER BY codcircunscripcion';
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	
	$circunscripcion = array();
	foreach($rows as $row){
		array_push($circunscripcion,$row);
	}
	
	$sqlite->close();
	echo json_encode($circunscripcion);
	unset($sqlite);
?>