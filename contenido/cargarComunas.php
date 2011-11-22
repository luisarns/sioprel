<?php
    if (isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        require_once 'conexionSQlite.php';

        $sqlite = new SPSQLite($pathDB);
        
        $query = "SELECT idcomuna,codcomuna,descripcion FROM pcomuna WHERE coddivipol LIKE" 
               . " $codcortodivipol || '%' ORDER BY codcomuna,descripcion";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();
        
        echo "Comuna : <select id='selcomuna' name='comuna' onChange='comunaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        if($sqlite->numRows() > 1) {
            foreach ($rs as $row) {
                echo "<option value = '" . $row['idcomuna'] . "'>" . $row['codcomuna'] . "-" . utf8_encode($row['descripcion']) . "</option>";
            }
        } else if($sqlite->numRows() == 1){
            echo "<option value = '" . $rs['idcomuna'] . "'>" . $rs['codcomuna'] . "-" . utf8_encode($rs['descripcion']) . "</option>";
        }
        echo '</select>';

        $sqlite->close(); 
        unset($sqlite);
    }
?>