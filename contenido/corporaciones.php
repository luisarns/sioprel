<?php
    include_once('conexion.php');

    $txt = "";
    if (isset($tipoEleccion)) {
        $txt = " WHERE tipoeleccion = $tipoEleccion ";
    }

    $coneccion = ibase_connect($host, $username, $password)
                     or die ('No se pudo conectar: ' . ibase_errmsg());

    $query = "SELECT codcorporacion,descripcion FROM pcorporaciones $txt ORDER"
           . " BY codcorporacion ";

    $result = ibase_query($coneccion, $query);
    $corporaciones = array();

    while ($row = ibase_fetch_object($result)) {
        $corporacion = array();
        $corporacion['id'] = $row->CODCORPORACION;
        $corporacion['nombre'] = utf8_encode($row->DESCRIPCION);
        array_push($corporaciones, $corporacion);
    }

    ibase_free_result($result);
    ibase_close($coneccion);
?>