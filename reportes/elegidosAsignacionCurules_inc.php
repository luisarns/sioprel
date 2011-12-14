<?php
    
    $codcorporacion = $_GET['corporacion'];
    $nivcorpo  = getNivelCorporacion($codcorporacion);
    
    $coddivcorto = $_GET['departamento'];
    $codnivel = 1;
    
    if (isset($_GET['municipio'])&& $_GET['municipio'] != '-') {
        $coddivcorto .= $_GET['municipio'];
        $codnivel = $codnivel + 1;
    }

    $codcordiv   = substr($coddivcorto, 0, getNumDigitos($nivcorpo));

    $filtroComuna = "";
    $hayComuna = false;
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
        $filtroComuna = "AND idcomuna = " . $_GET['comuna'];
        $hayComuna = true;
    }

    $query=<<<EOF
    SELECT pc.codpartido as codpartido, pc.codcandidato as codcandidato, pc.nombres as nombres, pc.apellidos as apellidos,
    pp.descripcion as descripcion,sum(dd.numvotos) as votos
    FROM 
        ( SELECT codpartido, descripcion
          FROM PPARTIDOS ) pp,
        ( SELECT codpartido,codcandidato,idcandidato,nombres,apellidos
          FROM PCANDIDATOS
          WHERE codcorporacion = $codcorporacion 
          AND coddivipol LIKE '$codcordiv' || '%'
          AND codnivel = $nivcorpo AND codcandidato <> 0 
          AND elegido <> 0 $filtroComuna ) pc,
        ( SELECT * 
          FROM DDETALLEBOLETIN 
          WHERE coddivipol LIKE '$coddivcorto' || '%' 
          AND codnivel = $nivcorpo AND codcorporacion = $codcorporacion $filtroComuna ) dd
    WHERE pc.codpartido = pp.codpartido AND pc.idcandidato = dd.idcandidato
    GROUP BY pc.codpartido, pc.codcandidato
    ORDER BY votos DESC
EOF;

    $coddivipol = str_pad($coddivcorto,9,'0');
    
    $queryCurules =<<<FEO
        SELECT numcurules,cuociente,cifrarepartidora
        FROM pcurules
        WHERE coddivipol = '$coddivipol'
        AND codnivel = $codnivel
        AND codcorporacion = $codcorporacion
        $filtroComuna
FEO;
    
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($query);
    $result = $sqlite->returnRows();
    
    $sqlite->query($queryCurules);
    $resultCurules = $sqlite->returnRows();
    
    $nocurules = (int)$resultCurules[0]['NUMCURULES'];
    $cuociente = (int)$resultCurules[0]['CUOCIENTE'];
    $cifrarepartidora = (int)$resultCurules[0]['CIFRAREPARTIDORA'];
    
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['DESCRIPCION'];
    //Fin de la consulta

    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivcorto,$codnivel);

    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    
    $nomDivipol = "";
    foreach($resultDivipol as $row) {
        $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivcorto, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nomDivipol = $nomDivipol . ' ' . $resultDivipol[0]['DESCRIPCION'];
    }

    $sqlite->close(); 
    unset($sqlite);

?>