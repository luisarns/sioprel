<?php
    $coddepto = $_GET['opcion'];
    $corpo  = $_GET['corporacion'];
    
    if (isset($coddepto) && is_numeric($coddepto) ) {
        
        require_once 'conexionSQlite.php';
        
        $sqlite = new SPSQLite($pathDB);
        
        $query = "SELECT codmunicipio,descripcion FROM pdivipol WHERE codnivel = 2 " 
               . "AND coddepartamento = '$coddepto' ORDER BY codmunicipio";
        
        $sqlite->query($query);
        $rs = $sqlite->returnRows();


        $fun = ($corpo != 5)?"cargarZonas":"cargarComunas";

        echo "Municipio&nbsp;<select name='municipio' id='selmunicipio' onChange='$fun(this.value)'>";
        echo "<option value = '-' >-Ninguna-</option>";
        foreach ($rs as $row) {
            echo "<option value = '" . $row['codmunicipio'] . "' >" . str_pad($row['codmunicipio'], 3, '0', STR_PAD_LEFT) . '-' 
             . utf8_encode($row['descripcion']) . "</option>";
        }
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>