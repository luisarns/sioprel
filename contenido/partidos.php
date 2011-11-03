<?php
	require('conexion.php');
	
	$query = "SELECT codpartido,descripcion FROM ppartidos ORDER BY descripcion";
	$coneccion = ibase_connect($host, $username, $password) or die ("No se pudo conectar la base de datos");
	$result = ibase_query($coneccion, $query);
	
	$partidos = array();
	
	while ($row = ibase_fetch_object($result)) {
		$partido = array();
		$partido['id'] = $row->CODPARTIDO;
		$partido['nombre'] = utf8_encode($row->DESCRIPCION);
		array_push($partidos, $partido);
	}
	
	ibase_free_result($result);
	ibase_close($coneccion);
?>