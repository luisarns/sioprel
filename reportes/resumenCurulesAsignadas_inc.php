<?php
    
    $codcorporacion = $_GET['corporacion'];
    $coddivcorto = $_GET['departamento'];
    $codnivel = 1;

    if (isset($_GET['municipio'])&& $_GET['municipio'] != '-') {
        $coddivcorto .= $_GET['municipio'];
        $codnivel = $codnivel + 1;
    }

    $coddivipol = str_pad($coddivcorto,9,'0');

    $txt = "";
    $hayComuna = false;
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
            $txt = "AND pcp.idcomuna = ".$_GET['comuna'];
            $hayComuna = true;
    }
    
    $query =<<<EOF
        SELECT pp.codpartido as codpartido , pp.descripcion as descripcion, pcp.numcurules as numcurules, pcp.totalvotos as totalvotos
        FROM ppartidos pp, pcurulespartidos pcp
        WHERE pp.codpartido = pcp.codpartido 
        AND pcp.coddivipol = '$coddivipol'
        AND pcp.codnivel = $codnivel
        AND pcp.codcorporacion = $codcorporacion
        $txt
        ORDER BY pcp.numcurules
EOF;
        
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($query);
    $result = $sqlite->returnRows();
    
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['descripcion'];
    //Fin de la consulta
    
    
    //Codigo para obtener la descripcion de la divipol desde la raiz
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivipol,$codnivel);

    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    
    $nomDivipol = "";
    foreach($resultDivipol as $row){
        $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($codcordivi, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nomDivipol = $nomDivipol . ' ' . $resultDivipol[0]['descripcion'];
    }

    $sqlite->close(); 
    unset($sqlite);
    
?>