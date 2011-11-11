<?php
    if(isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        require('conexion.php');

        $coneccion = ibase_connect($host, $username, $password) 
                or die ("No se pudo conectar:" . ibase_errmsg());

        $query = "SELECT codzona,descripcion FROM pdivipol WHERE coddivipol LIKE $codcortodivipol || '%' "
               . "AND codnivel = 3 ORDER BY codzona,descripcion";

        $result = ibase_query($coneccion, $query);

        echo "Zona : <select id='selzona' name='zona' onChange='zonaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        while($row = ibase_fetch_object($result)) {
                echo "<option value = '$row->CODZONA' >" . str_pad($row->CODZONA, 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($row->DESCRIPCION) . "</option>";
        }	
        echo "</select>";

        ibase_free_result($result);
        ibase_close($coneccion);
    }
?>