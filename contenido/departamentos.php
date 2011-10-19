<?php
	require('conexion.php');
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT coddepartamento,descripcion FROM pdivipol WHERE codnivel = 1  ORDER BY descripcion";
	$result = ibase_query($cnh,$query);
	$departamentos = array();
	while($row = ibase_fetch_object($result)) {
			$departamento = array();
			$departamento['id'] = $row->CODDEPARTAMENTO;
			$departamento['nombre'] = $row->DESCRIPCION;
			array_push($departamentos,$departamento);
	}
	ibase_free_result($result);
	ibase_close($cnh);
?>