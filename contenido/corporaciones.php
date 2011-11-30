<?php
    include_once 'conexionSQlite3.php';

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
    if(isset ($rs)) {
        foreach($rs as $row) {
                $corporacion = array();
                $corporacion['id'] = $row['CODCORPORACION'];
                $corporacion['nombre'] = $row['DESCRIPCION'];
                array_push($corporaciones, $corporacion);
        }
    }
    $sqlite->close(); 
    unset($sqlite);
    
?>