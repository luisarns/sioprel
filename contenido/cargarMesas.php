<?php
    if (isset ($_GET['divipol']) && isset ($_GET['corporacion'])) {
        require('conexion.php');

        $coddivipol  = $_GET['divipol'];
        $codcorpo    = $_GET['corporacion'];

        $coneccion = ibase_connect($host, $username, $password) 
                or die ('No se pudo conectar: ' . ibase_errmsg());

        $query = "SELECT codtransmision,codmesa FROM pmesas WHERE coddivipol "
               . "= '$coddivipol' AND codnivel = 4 AND codcorporacion = $codcorpo ORDER BY codmesa";

        $result = ibase_query($coneccion, $query);

        echo "Mesa : <select id='selmesa' name='mesa'>";
        echo "<option value = '-' >-Ninguna-</option>";
        while($row = ibase_fetch_object($result)) {
            echo "<option value = '$row->CODTRANSMISION' >$row->CODMESA</option>";
        }	
        echo "</select>";

        ibase_free_result($result);
        ibase_close($coneccion);
    }
?>