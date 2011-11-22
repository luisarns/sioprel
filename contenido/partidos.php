<?php
    include_once 'conexionSQlite.php';

    $sqlite = new SPSQLite($pathDB);
    $query = "SELECT codpartido,descripcion FROM ppartidos ORDER BY codpartido";
    
    $sqlite->query($query);
    
    $rs = $sqlite->returnRows(); 
    $partidos = array();
        
	foreach($rs as $row) {
		$partido = array();
		$partido['id'] = $row['codpartido'];
		$partido['nombre'] = str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT) . '-' . utf8_encode($row['descripcion']);
		array_push($partidos, $partido);
	}
    
    $sqlite->close(); 
    unset($sqlite);
    
?>