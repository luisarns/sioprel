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
        SELECT lpad(pp.codpartido,3,'0') as codigo, pp.descripcion, pcp.numcurules, pcp.totalvotos
        FROM ppartidos pp, pcurulespartidos pcp
        WHERE pp.codpartido = pcp.codpartido 
        AND pcp.coddivipol = '$coddivipol'
        AND pcp.codnivel = $codnivel
        AND pcp.codcorporacion = $codcorporacion
        $txt
        ORDER BY pcp.numcurules
EOF;
        
    $firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: " . ibase_errmsg());
    $result   = ibase_query($firebird, $query);
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);
    //Fin de la consulta
    
    
    //Codigo para obtener la descripcion de la divipol desde la raiz
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivipol,$codnivel);

    $resultDivipol = ibase_query($firebird, $queryDivipol);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($codcordivi, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }

    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
    
    
?>