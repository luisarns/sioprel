<?php
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = "";
    $txt1 = "";
    $hayComuna = false;
    if ($_GET['comuna'] !='-') {
        $txt = " AND pd.idcomuna = " . $_GET['comuna'];
        $txt .= " AND pc.idcomuna = " . $_GET['comuna'];
        $txt1 = " AND pc.idcomuna = " . $_GET['comuna'];
        $hayComuna = true;
    }
    
    $nivcorpo = getNivelCorporacion($corporacion);
    $coddivcorpo = str_pad(substr($coddivipol, 0, getNumDigitos($nivcorpo)),9,'0');
    
    
    $queryVotacionPartido =<<<VTP
        SELECT pp.codpartido, pp.descripcion, sum(mv.numvotos) as votos
        FROM ppartidos pp, pcandidatos pc, pdivipol pd, pmesas pm, mvotos mv
        WHERE pp.codpartido = pc.codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pd.coddivipol LIKE '$coddivipol' || '%'
        AND pd.codnivel = 4
        AND pd.coddivipol = pm.coddivipol
        AND pc.codnivel = $nivcorpo
        AND pc.codcorporacion = $corporacion
        AND pm.codcorporacion = $corporacion
        AND pm.codtransmision = mv.codtransmision
        AND pc.idcandidato = mv.idcandidato
        $txt
        GROUP BY pp.codpartido, pp.descripcion
VTP;
    
    $queryPartidoAvalados = <<<PAV
        SELECT pp.codpartido, pp.descripcion, count(pc.idcandidato) as avalados
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        $txt1
        GROUP BY pp.codpartido, pp.descripcion
PAV;
    
    $queryPartidoElegidos = <<<PEL
        SELECT pp.codpartido, pp.descripcion, count(pc.idcandidato) as elegidos
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        AND pc.elegido <> '0'
        $txt1
        GROUP BY pp.codpartido, pp.descripcion
PEL;
    
    //Ejecutar las consultas contra la base de datos
    $firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    
    $resultVotacionPartido  = ibase_query($firebird,$queryVotacionPartido);
    $votosPartido = array();
    while ($row = ibase_fetch_object($resultVotacionPartido)) {
        $record = array();
        $record['codpartido'] = $row->CODPARTIDO;
        $record['descripcion'] = $row->DESCRIPCION;
        $record['avalados'] = 0;
        $record['elegidos'] = 0;
        $record['votos'] = $row->VOTOS;
        array_push($votosPartido,$record);
    }
    
    //Asigno el numero de candidatos avalados al partido
    $resultPartidoAvalados  = ibase_query($firebird,$queryPartidoAvalados);
    $arrAvalados = array();
    while ($row = ibase_fetch_object($resultPartidoAvalados)) {
        for ($i = 0; $i < count($votosPartido); $i++) {
            if($row->CODPARTIDO == $votosPartido[$i]['codpartido'] ){
                $votosPartido[$i]['avalados'] =  $row->AVALADOS;
                break;
            }
        }
    }
    
    //Asigno el numero de candidatos elegidos al partido
    $resultPartidoElegidos  = ibase_query($firebird,$queryPartidoElegidos);
    $arrElegidos = array();
    while ($row = ibase_fetch_object($resultPartidoElegidos)) {
        for ($i = 0; $i < count($votosPartido); $i++) {
            if ($row->CODPARTIDO == $votosPartido[$i]['codpartido'] ) {
                $votosPartido[$i]['elegidos'] =  $row->ELEGIDOS;
                break;
            }
        }
    }
    
    
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $corporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);
    
    
    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    
    $queryDivipoles = getQueryDivipolCompleta($coddivipol,$codnivel);
    
    $nmDepartamento = "";
    $nmMunicipio = "";
    $nmZona = "";
    $nmPueto = "";
    $nmComuna = "";
    
    $resultDivipol = ibase_query($firebird, $queryDivipoles);
    while ($row = ibase_fetch_object($resultDivipol)) {
        switch($row->CODNIVEL){
            case 1:
                $nmDepartamento = utf8_encode($row->DESCRIPCION);
                break;
            case 2:
                $nmMunicipio = utf8_encode($row->DESCRIPCION);
                break;
            case 3:
                $nmZona = utf8_encode($row->DESCRIPCION);
                break;
            case 4:
                $nmPueto = utf8_encode($row->DESCRIPCION);
                break;
        }
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['comuna'];
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nmComuna = utf8_encode($row->DESCRIPCION);
        $nmZona = ""; //Dejo la zona basia
    }
    

    //Cierro la coneccion a la base de datos
    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
    ibase_free_result($resultPartidoElegidos);
    ibase_free_result($resultPartidoAvalados);
    ibase_free_result($resultVotacionPartido);
    ibase_close($firebird);
?>