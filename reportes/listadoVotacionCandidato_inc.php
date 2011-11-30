<?php

    $codcorporacion = $_GET['corporacion'];
    $nivcorpo  = getNivelCorporacion($codcorporacion);

    $depto = $_GET['departamento'];
    $muncp = ($_GET['municipio']!="-")?$_GET['municipio']:"";

    $coddivcorto = $depto.$muncp;
    $codcordiv   = substr($coddivcorto,0,getNumDigitos($nivcorpo));

    $filtroComuna = "";
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
        $filtroComuna = "AND idcomuna = " . $_GET['comuna'];
        $hayComuna = true;
    }

    $urlReportes .="&formato=";
    
    $query =<<<EOF
    SELECT pc.codpartido as codpartido, pc.codcandidato as codcandidato, pc.nombres as nombres, pc.apellidos as apellidos,
    pp.descripcion as descripcion,sum(dd.numvotos) as votos
    FROM PPARTIDOS pp,
      ( SELECT codpartido,codcandidato,idcandidato,nombres,apellidos
        FROM PCANDIDATOS 
        WHERE codcorporacion = $codcorporacion
        AND coddivipol LIKE '$codcordiv' || '%'
        AND codnivel = $nivcorpo AND codcandidato <> 0 $filtroComuna ) pc,       
      ( SELECT * 
        FROM DDETALLEBOLETIN 
        WHERE coddivipol LIKE '$coddivcorto' || '%' 
        AND codnivel = $nivcorpo AND codcorporacion = $codcorporacion $filtroComuna ) dd  
    WHERE pc.codpartido = pp.codpartido AND pc.idcandidato = dd.idcandidato
    GROUP BY pc.codpartido, pc.codcandidato
    ORDER BY votos DESC
EOF;
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();

    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['DESCRIPCION'];

    
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
        $nmComuna = $resultDivipol[0]['DESCRIPCION'];
        $nmZona = "";
    }

    $sqlite->close(); 
    unset($sqlite)
        
?>