<?php
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    $codpartido = $_GET['codpartido'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = "";
    if ($_GET['comuna'] !='-') {
        $txt = " AND pc.idcomuna = " . $_GET['comuna'];
    }
    
    $nivcorpo = getNivelCorporacion($corporacion);
    $coddivcorpo = str_pad(substr($coddivipol, 0, getNumDigitos($nivcorpo)),9,'0');
    
    
    $queryInscritos = <<<PAV
        SELECT pc.codcandidato, pc.nombres, pc.apellidos , pc.elegido
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pp.codpartido = $codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        $txt
        ORDER BY pc.elegido
PAV;
    
    $firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $resultInscritos  = ibase_query($firebird,$queryInscritos);
    
     $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $corporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);
    
    
    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    
    $queryDivipoles = getQueryDivipolCompleta($coddivipol,$codnivel);
    
    $resultDivipol = ibase_query($firebird, $queryDivipoles);
    $nomDivipol = "";
    while ($row = ibase_fetch_object($resultDivipol)) {
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['comuna'];
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    //Fin del codigo
    
    
    //Para obtener el nombre del partido
    $queryPartido = <<<PAR
        SELECT descripcion
        FROM ppartidos
        WHERE codpartido = $codpartido
PAR;
    $resulPartido = ibase_query($firebird, $queryPartido);
    $row = ibase_fetch_object($resulPartido);
    $nomPartido = utf8_encode($row->DESCRIPCION);
    
    
    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
    
?>