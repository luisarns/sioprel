<?php
	require('conexion.php');
	
	$coneccion = ibase_connect($host, $username, $password) or die ('No se pudo conectar la base de datos');
	$query = 'SELECT coddivipol,coddepartamento,descripcion FROM pdivipol WHERE codnivel = 1 ORDER BY descripcion';
	$result = ibase_query($coneccion, $query);
	
	$departamentos = array();
	
	while ($row = ibase_fetch_object($result)) {
            $departamento = array();
            $departamento['coddivipol'] = $row->CODDIVIPOL;
            $departamento['id'] = $row->CODDEPARTAMENTO;
            $departamento['nombre'] = str_pad($row->CODDEPARTAMENTO, 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($row->DESCRIPCION);
            array_push($departamentos, $departamento);
	}
	
	ibase_free_result($result);
	ibase_close($coneccion);
?>