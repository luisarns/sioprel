<?php
    if(isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        
        require_once 'conexionSQlite3.php';
        
        $sqlite = new SPSQLite($pathDB);

        $query = "SELECT codzona,descripcion FROM pdivipol WHERE coddivipol LIKE $codcortodivipol || '%' "
               . "AND codnivel = 3 ORDER BY codzona,descripcion";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();

        echo "Zona : <select id='selzona' name='zona' onChange='zonaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        
        foreach ($rs as $row) {
            echo "<option value = '" . $row['CODZONA'] . "' >" . str_pad($row['CODZONA'], 2, '0', STR_PAD_LEFT) . '-' . $row['DESCRIPCION'] . "</option>";
        }
        
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>