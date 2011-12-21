<?php
    $coddepto = $_GET['opcion'];
    $corpo  = $_GET['corporacion'];
    
    if (isset($coddepto) && is_numeric($coddepto) ) {
        
        require_once 'conexionSQlite3.php';
        
        $sqlite = new SPSQLite($pathDB);
        
        $query = "SELECT codmunicipio,descripcion FROM pdivipol WHERE codnivel = 2 " 
               . "AND coddepartamento = '$coddepto' ORDER BY codmunicipio";
        
        if($corpo == 5){
            $query = " SELECT DISTINCT pd.codmunicipio,pd.descripcion 
            FROM pdivipol pd INNER JOIN pcomuna pc
            ON pd.coddivipol = pc.coddivipol
            WHERE pc.idcomuna <> 0 AND pc.codnivel = pd.codnivel AND pd.coddivipol LIKE '$coddepto' || '%'";
        }
        
//        echo "<br/>" . $query . "<br/>";
        
        $sqlite->query($query);
        $rs = $sqlite->returnRows();


        $fun = ($corpo != 5)?"cargarZonas":"cargarComunas";

        echo "Municipio&nbsp;<select name='municipio' id='selmunicipio' onChange='$fun(this.value)'>";
        echo "<option value = '-' >-Ninguna-</option>";
        foreach ($rs as $row) {
            echo "<option value = '" . $row['CODMUNICIPIO'] . "' >" . str_pad($row['CODMUNICIPIO'], 3, '0', STR_PAD_LEFT) . '-' 
             . htmlentities($row['DESCRIPCION'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . "</option>";
        }
        echo "</select>";

        $sqlite->close(); 
        unset($sqlite);
    }
?>