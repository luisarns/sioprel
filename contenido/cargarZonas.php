<?php
    if(isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        
        require_once 'conexionSQlite.php';
        
        $sqlite = new SPSQLite($pathDB);

        $query = "SELECT codzona,descripcion FROM pdivipol WHERE coddivipol LIKE $codcortodivipol || '%' "
               . "AND codnivel = 3 ORDER BY codzona,descripcion";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();

        echo "Zona : <select id='selzona' name='zona' onChange='zonaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        if($sqlite->numRows() > 1) {
            foreach ($rs as $row) {
                echo "<option value = '" . $row['codzona'] . "' >" . str_pad($row['codzona'], 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($row['descripcion']) . "</option>";
            }
        } else if($sqlite->numRows() == 1) {
           echo "<option value = '" . $rs['codzona'] . "' >" . str_pad($rs['codzona'], 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($rs['descripcion']) . "</option>";
        }
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>