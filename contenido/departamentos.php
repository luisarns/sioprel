<?php
    include_once FILE_SQLITE_CONECCION;
    
    $sqlite = new SPSQLite($pathDB);
    $query = 'SELECT coddivipol,coddepartamento,descripcion FROM pdivipol ' 
           . 'WHERE codnivel = 1 ORDER BY descripcion';
    
    $sqlite->query($query);
    
    $rs = $sqlite->returnRows();
    $departamentos = array();
    
	foreach($rs as $row) {
		$departamento = array();
		$departamento['coddivipol'] = $row['coddivipol'];
		$departamento['id'] = $row['coddepartamento'];
		$departamento['nombre'] =  str_pad($row['coddepartamento'], 2, '0', STR_PAD_LEFT). '-' . utf8_encode($row['descripcion']);
		array_push($departamentos, $departamento);
	}
    
    $sqlite->close(); 
    unset($sqlite);
    
?>