<?php
    if (isset ($_GET['divipol']) && isset ($_GET['corporacion'])) {
        require_once 'conexionSQlite3.php';

        $coddivipol  = $_GET['divipol'];
        $codcorpo    = $_GET['corporacion'];

        $sqlite = new SPSQLite($pathDB);

        $query = "SELECT codtransmision,codmesa FROM pmesas WHERE coddivipol "
               . "= '$coddivipol' AND codnivel = 4 AND codcorporacion = $codcorpo ORDER BY codmesa";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();

        echo "Mesa : <select id='selmesa' name='mesa'>";
        echo "<option value = '-' >-Ninguna-</option>";
        if(isset ($rs)) {
            foreach ($rs as $row) {
                echo "<option value = '" . $row['CODTRANSMISION'] . "' >" . $row['CODMESA'] . "</option>";
            }
        }         
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>