<?php
    include_once 'conexionSQlite.php';

    $txt = "";
    if (isset($tipoEleccion)) {
        $txt = " WHERE tipoeleccion = $tipoEleccion ";
    }

    $sqlite = new SPSQLite($pathDB);
    $query = "SELECT codcorporacion,descripcion FROM pcorporaciones $txt ORDER"
           . " BY codcorporacion ";

    $sqlite->query($query);
    $rs = $sqlite->returnRows(); 
    
    $corporaciones = array();

    if ($sqlite->numRows() > 1) {
        foreach($rs as $row) {
            $corporacion = array();
            $corporacion['id'] = $row['codcorporacion'];
            $corporacion['nombre'] = utf8_encode($row['descripcion']);
            array_push($corporaciones, $corporacion);
        }
    } else if ($sqlite->numRows() == 1) {
        $corporacion = array();
        $corporacion['id'] = $rs['codcorporacion'];
        $corporacion['nombre'] = utf8_encode($rs['descripcion']);
        array_push($corporaciones, $corporacion);
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>