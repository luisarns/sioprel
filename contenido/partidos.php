<?php
    include_once 'conexionSQlite.php';

    $sqlite = new SPSQLite($pathDB);
    $query = "SELECT codpartido,descripcion FROM ppartidos ORDER BY codpartido";
    
    $sqlite->query($query);
    
    $rs = $sqlite->returnRows(); 
    $partidos = array();
    
    if ($sqlite->numRows() > 1) {
        foreach($rs as $row) {
            $partido = array();
            $partido['id'] = $row['codpartido'];
            $partido['nombre'] = str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT) . '-' . utf8_encode($row['descripcion']);
            array_push($partidos, $partido);
        }
    } else if ($sqlite->numRows() == 1) {
        $partido = array();
        $partido['id'] = $rs['codpartido'];
        $partido['nombre'] = str_pad($rs['codpartido'], 3, '0', STR_PAD_LEFT) . '-' . utf8_encode($rs['descripcion']);
        array_push($partidos, $partido);
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>