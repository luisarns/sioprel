<?php
    include_once 'conexionSQlite3.php';
    
    $sqlite = new SPSQLite($pathDB);
    $query = 'SELECT coddivipol,coddepartamento,descripcion FROM pdivipol ' 
           . 'WHERE codnivel = 1 ORDER BY coddepartamento,descripcion';
    
    $sqlite->query($query);
    
    $rs = $sqlite->returnRows();
    $departamentos = array();
    
    if(isset ($rs)) {
        foreach($rs as $row) {
            $departamento = array();
            $departamento['coddivipol'] = $row['CODDIVIPOL'];
            $departamento['id'] = $row['CODDEPARTAMENTO'];
            $departamento['nombre'] =  str_pad($row['CODDEPARTAMENTO'], 2, '0', STR_PAD_LEFT). '-' . utf8_encode($row['DESCRIPCION']);
            array_push($departamentos, $departamento);
        }
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>