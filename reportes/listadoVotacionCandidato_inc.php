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
    SELECT lpad(pp.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
    FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
    WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
    AND pd.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
    AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0
    AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $txt
    AND pm.codcorporacion = $codcorporacion
    GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;

    $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $result   = ibase_query($firebird,$query);
	

    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);

    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivcorto,2);

        
    $resultDivipol = ibase_query($firebird, $queryDivipol);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivcorto, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }

    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
        
?>