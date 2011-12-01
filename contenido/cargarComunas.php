<?php
    if (isset($_GET['divipol'])) {
        $codcortodivipol  = $_GET['divipol'];
        require_once 'conexionSQlite3.php';

        $sqlite = new SPSQLite($pathDB);
        
        $query = "SELECT idcomuna,codcomuna,descripcion FROM pcomuna WHERE coddivipol LIKE" 
               . " $codcortodivipol || '%' ORDER BY codcomuna,descripcion";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();
        
        echo "Comuna : <select id='selcomuna' name='comuna' onChange='comunaCargaPuesto(this.value)' >";
        echo "<option value = '-' >-Ninguna-</option>";
        if(isset ($rs)){
            foreach ($rs as $row) {
                echo "<option value = '" . $row['IDCOMUNA'] . "'>" . $row['CODCOMUNA'] . "-" . $row['DESCRIPCION'] . "</option>";
            }
        }
        echo '</select>';

        $sqlite->close(); 
        unset($sqlite);
    }
?>