<?php
    $divipol  = $_GET['divipol'];
    $corpo    = $_GET['corporacion'];

    require('conexion.php');

    $coneccion = ibase_connect($host,$username,$password) or die ('No se pudo conectar la base de datos');
    
    $query = "SELECT codtransmision,codmesa FROM pmesas WHERE coddivipol "
           . "= '$divipol' AND codnivel = 4 AND codcorporacion = $corpo ORDER BY codmesa";
    
    $result = ibase_query($coneccion,$query);

    echo "Mesa : <select id='selmesa' name='mesa'>";
    echo "<option value = '-' >-Ninguna-</option>";
    while($row = ibase_fetch_object($result)) {
            echo "<option value = '$row->CODTRANSMISION' >$row->CODMESA</option>";
    }	
    echo '</select>';

    ibase_free_result($result);
    ibase_close($coneccion);
?>