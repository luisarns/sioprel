<?php
    include_once 'conexionSQlite.php';
    
    $sqlite = new SPSQLite($pathDB);
    $query = 'SELECT coddivipol,coddepartamento,descripcion FROM pdivipol ' 
           . 'WHERE codnivel = 1 ORDER BY descripcion';
    
    $sqlite->query($query);
    
    $rs = $sqlite->returnRows();
    $departamentos = array();
    
    if ($sqlite->numRows() > 1) {
        foreach($rs as $row) {
            $departamento = array();
            $departamento['coddivipol'] = $row['coddivipol'];
            $departamento['id'] = $row['coddepartamento'];
            $departamento['nombre'] =  str_pad($row['coddepartamento'], 2, '0', STR_PAD_LEFT). '-' . utf8_encode($row['descripcion']);
            array_push($departamentos, $departamento);
        }
    } else if ($sqlite->numRows() == 1) {
        $departamento = array();
        $departamento['coddivipol'] = $rs['coddivipol'];
        $departamento['id'] = $rs['coddepartamento'];
        $departamento['nombre'] = str_pad($rs['coddepartamento'], 2, '0', STR_PAD_LEFT). '-' . utf8_encode($rs['descripcion']);
        array_push($departamentos, $departamento);
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>