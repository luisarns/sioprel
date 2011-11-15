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
        SELECT lpad(pc.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo  ,pc.nombres, 
        pc.apellidos ,pp.descripcion,sum(mv.numvotos) as votos
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
    
    $firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: " . ibase_errmsg());
    $result   = ibase_query($firebird, $query);
    
    $resultCurules = ibase_query($firebird, $queryCurules);
    $row = ibase_fetch_object($resultCurules);
    
    $nocurules = $row->NUMCURULES;
    $cuociente = $row->CUOCIENTE;
    $cifrarepartidora = $row->CIFRAREPARTIDORA;
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);
    //Fin de la consulta

    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivcorto,$codnivel);

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