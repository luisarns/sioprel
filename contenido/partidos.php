<?php
    include_once 'conexionSQlite3.php';

    $sqlite = new SPSQLite($pathDB);
    $query = "SELECT CODPARTIDO, DESCRIPCION FROM ppartidos ORDER BY codpartido";

    $sqlite->query($query);

    $rs = $sqlite->returnRows(); 
    $partidos = array();
    
    if (isset($rs)) {
        $i = 0;
        foreach ($rs as $row) {
            $partidos[$i]['id'] = $row['CODPARTIDO'];
            $partidos[$i]['nombre'] = str_pad($row['CODPARTIDO'], 3, '0', STR_PAD_LEFT) . '-' . $row['DESCRIPCION'];
            $i++;
        }
    }
    
    $sqlite->close(); 
    unset($sqlite);
?>