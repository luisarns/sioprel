<?php
    if (isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        require('conexion.php');

        $coneccion = ibase_connect($host, $username, $password) 
                or die ("No se pudo conectar:" . ibase_errmsg());
        
        $query = "SELECT idcomuna,codcomuna,descripcion FROM pcomuna WHERE coddivipol LIKE" 
               . " $codcortodivipol || '%' ORDER BY codcomuna,descripcion";

        $result = ibase_query($coneccion,$query);

        echo "Comuna : <select id='selcomuna' name='comuna' onChange='comunaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        while ($row = ibase_fetch_object($result)) {
            echo "<option value = '$row->IDCOMUNA' >$row->CODCOMUNA-" . utf8_encode($row->DESCRIPCION) . "</option>";
        }
        echo '</select>';

        ibase_free_result($result);
        ibase_close($coneccion);
    }
?>