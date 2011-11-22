<?php
    if (isset ($_GET['divipol']) && isset ($_GET['corporacion'])) {
        require_once 'conexionSQlite.php';

        $coddivipol  = $_GET['divipol'];
        $codcorpo    = $_GET['corporacion'];

        $sqlite = new SPSQLite($pathDB);

        $query = "SELECT codtransmision,codmesa FROM pmesas WHERE coddivipol "
               . "= '$coddivipol' AND codnivel = 4 AND codcorporacion = $codcorpo ORDER BY codmesa";

        $sqlite->query($query);
        $rs = $sqlite->returnRows();

        echo "Mesa : <select id='selmesa' name='mesa'>";
        echo "<option value = '-' >-Ninguna-</option>";
        if($sqlite->numRows() > 1) {
            foreach ($rs as $row) {
                echo "<option value = '" . $row['codtransmision'] . "' >" . $row['codmesa'] . "</option>";
            }
        } else if($sqlite->numRows() == 1) {
           echo "<option value = '" . $rs['codtransmision'] . "' >" . $rs['codmesa'] . "</option>";
        }           
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>