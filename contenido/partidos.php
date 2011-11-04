<?php
	require('conexion.php');
	
	$query = "SELECT codpartido,descripcion FROM ppartidos ORDER BY codpartido";
	$coneccion = ibase_connect($host, $username, $password) or die ("No se pudo conectar la base de datos");
	$result = ibase_query($coneccion, $query);
	
	$partidos = array();
	
	while ($row = ibase_fetch_object($result)) {
		$partido = array();
		$partido['id'] = $row->CODPARTIDO;
		$partido['nombre'] = str_pad($row->CODPARTIDO, 3, '0', STR_PAD_LEFT) . '-' . utf8_encode($row->DESCRIPCION);
		array_push($partidos, $partido);
	}
	
	ibase_free_result($result);
	ibase_close($coneccion);
?>