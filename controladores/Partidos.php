<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query ='SELECT codpartido as id , descripcion as nombre FROM ppartidos ORDER BY codpartido';
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	
	$circunscripcion = array();
	if($sqlite->numRows() > 1 ){
		foreach($rows as $row){
			$row['nombre'] = htmlentities($row['nombre']);
			array_push($circunscripcion,$row);
		}
	} else if ($sqlite->numRows() == 1){
		$rows['nombre'] = htmlentities($rows['nombre']);
		array_push($circunscripcion,$rows);
	}
	
	$sqlite->close();
	echo json_encode($circunscripcion);
	unset($sqlite);
?>