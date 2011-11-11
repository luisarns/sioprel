<?php
    $coddepto = $_GET['opcion'];
    $corpo  = $_GET['corporacion'];

    if (isset($coddepto) && is_numeric($coddepto) ) {
        
        include_once('conexion.php');

        $coneccion = ibase_connect($host,$username,$password) 
                or die ("No se pudo conectar: " . ibase_errmsg()); 
        
        $query = "SELECT codmunicipio,descripcion FROM pdivipol WHERE codnivel = 2 " 
               . "AND coddepartamento = '$coddepto' ORDER BY codmunicipio";

        $result = ibase_query($coneccion,$query);

        $fun = ($corpo != 5)?"cargarZonas":"cargarComunas";

        echo "Municipio&nbsp;<select name='municipio' id='selmunicipio' onChange='$fun(this.value)'>";
        echo "<option value = '-' >-Ninguna-</option>";

        while($row = ibase_fetch_object($result)) {
            echo "<option value = '$row->CODMUNICIPIO' >" . str_pad($row->CODMUNICIPIO, 3, '0', STR_PAD_LEFT) . '-' 
                 . utf8_encode($row->DESCRIPCION) . "</option>";
        }
        echo "</select>";

        ibase_free_result($result);
        ibase_close($coneccion);
    }
?>