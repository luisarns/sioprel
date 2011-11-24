<?php

    $codcorporacion = $_GET['corporacion'];
    $nivcorpo  = getNivelCorporacion($codcorporacion);

    $depto = $_GET['departamento'];
    $muncp = ($_GET['municipio']!="-")?$_GET['municipio']:"";

    $coddivcorto = $depto.$muncp;
    $codcordiv   = substr($coddivcorto,0,getNumDigitos($nivcorpo));

    $txt = "";
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
        $txt = "AND pc.idcomuna = ".$_GET['comuna'];
        $txt .= " AND pd.idcomuna = ".$_GET['comuna'];
        $hayComuna = true;
    }

    $urlReportes .="&formato=";
    
    $query =<<<EOF
    SELECT pp.codpartido as codpartido, pc.codcandidato as codcandidato, pc.nombres as nombres, pc.apellidos as apellidos, pp.descripcion as descripcion, sum(mv.numvotos) as votos
    FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
    WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
    AND pd.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
    AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0
    AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $txt
    AND pm.codcorporacion = $codcorporacion
    GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();

    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = utf8_encode($resulCorporacion[0]['descripcion']);

    
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivcorto,2);

    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    
    $nomDivipol = "";
    foreach($resultDivipol as $row){
        $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivcorto, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = utf8_encode($resultDivipol[0]['descripcion']);
        $nmZona = "";
    }

    $sqlite->close(); 
    unset($sqlite)
        
?>