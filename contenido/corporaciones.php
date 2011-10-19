<?php
	require('conexion.php');
	
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT codcorporacion,descripcion FROM pcorporaciones ORDER BY codcorporacion";
	$result = ibase_query($cnh,$query);
	$corporaciones = array();
	while($row = ibase_fetch_object($result)) {
			$corporacion = array();
			$corporacion['id'] = $row->CODCORPORACION;
			$corporacion['nombre'] = $row->DESCRIPCION;
			array_push($corporaciones,$corporacion);
	}
	ibase_free_result($result);
	ibase_close($cnh);
?>