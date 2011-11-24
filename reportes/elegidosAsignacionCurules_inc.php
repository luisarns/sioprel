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

    $txt  = "";
    $txt1 = "";
    $hayComuna = false;
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
        $txt = "AND pc.idcomuna = " . $_GET['comuna'];
        $txt .= " AND pd.idcomuna = " . $_GET['comuna'];
        $txt1 = "AND idcomuna = ". $_GET['comuna'];
        $hayComuna = true;
    }

    $query=<<<EOF
        SELECT pc.codpartido as codpartido, pc.codcandidato as codcandidato ,pc.nombres as nombres, 
        pc.apellidos as apellidos ,pp.descripcion as descripcion ,sum(mv.numvotos) as votos
        FROM pmesas pm, mvotos mv, ppartidos pp, pcandidatos pc, pdivipol pd
        WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
        AND pp.codpartido = pc.codpartido
        AND pd.coddivipol LIKE '$coddivcorto' || '%'
        AND pc.coddivipol LIKE '$codcordiv' || '%' 
        AND pc.codnivel = $nivcorpo
        AND mv.codtransmision = pm.codtransmision 
        AND pc.codcandidato <> 0
        AND pc.idcandidato = mv.idcandidato 
        AND pc.codcorporacion = $codcorporacion
        AND pm.codcorporacion = $codcorporacion
        $txt
        AND pc.elegido <> '0'
        GROUP BY pc.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;

    $coddivipol = str_pad($coddivcorto,9,'0');
    
    $queryCurules =<<<FEO
        SELECT numcurules,cuociente,cifrarepartidora
        FROM pcurules
        WHERE coddivipol = '$coddivipol'
        AND codnivel = $codnivel
        AND codcorporacion = $codcorporacion
        $txt1
FEO;
    
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($query);
    $result = $sqlite->returnRows();
    
    $sqlite->query($queryCurules);
    $resultCurules = $sqlite->returnRows();
    
    $nocurules = $resultCurules[0]['numcurules'];
    $cuociente = $resultCurules[0]['cuociente'];
    $cifrarepartidora = $resultCurules[0]['cifrarepartidora'];
    
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['descripcion'];
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
        $nomDivipol = $nomDivipol . ' ' . $resultDivipol[0]['descripcion'];
    }

    $sqlite->close(); 
    unset($sqlite);

?>