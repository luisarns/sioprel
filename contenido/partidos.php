<?php
	require('conexion.php');
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT codpartido,descripcion FROM ppartidos ORDER BY descripcion";
	$result = ibase_query($cnh,$query);
	$partidos = array();
	while($row = ibase_fetch_object($result)) {
			$partido = array();
			$partido['id'] = $row->CODPARTIDO;
			$partido['nombre'] = $row->DESCRIPCION;
			array_push($partidos,$partido);
	}
	ibase_free_result($result);
	ibase_close($cnh);
?>