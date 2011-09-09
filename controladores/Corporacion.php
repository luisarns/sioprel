<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query ='SELECT codcorporacion as codcorpo , descripcion as descorpo, codnivel, tipoeleccion FROM pcorporaciones ORDER BY codcorporacion';
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	
	$corpo = array();
	foreach($rows as $row){
		$row['descorpo'] = htmlentities($row['descorpo']);
		array_push($corpo,$row);
	}
	
	$sqlite->close();
	echo json_encode($corpo);
	unset($sqlite);
?>