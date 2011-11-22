<?php

    if (isset($_GET['divipol'])) {
        $divipol  = $_GET['divipol'];    

        $txt = "";
        if (isset($_GET['idcomuna'])) {
                $txt = "AND idcomuna = ".$_GET['idcomuna'];
        }

        if (isset($_GET['zona'])) {
                $divipol .= $_GET['zona'];
        }

        require_once 'conexionSQlite.php';
        
        $sqlite = new SPSQLite($pathDB);
        
        $query = "SELECT coddivipol,codpuesto,descripcion FROM pdivipol WHERE coddivipol LIKE $divipol || '%' "
               . "AND codnivel = 4 $txt ORDER BY codpuesto,descripcion";
        
        $sqlite->query($query);
        $rs = $sqlite->returnRows();

        echo "Puesto : <select id='selpuesto' name='puesto' onChange='cargarMesas(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        foreach ($rs as $row) {
            echo "<option value = '" . $row['coddivipol'] . "' >" . str_pad($row['codpuesto'], 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($row['descripcion']) . "</option>";
        }
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>