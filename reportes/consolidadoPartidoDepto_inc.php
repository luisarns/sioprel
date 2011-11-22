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
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($queryVotacionPartido);
    $resultVotacionPartido = $sqlite->returnRows();
    $votosPartido = array();
    
    if (isset($resultVotacionPartido)) {
        foreach($resultVotacionPartido as $row) {
            $record = array();
            $record['codpartido'] = $row['codpartido'];
            $record['descripcion'] = $row['descripcion'];
            $record['avalados'] = 0;
            $record['elegidos'] = 0;
            $record['votos'] = $row['votos'];
            array_push($votosPartido,$record);
        }
    }
    
    //Asigno el numero de candidatos avalados al partido
    $sqlite->query($queryPartidoAvalados);
    $resultPartidoAvalados = $sqlite->returnRows();
    $arrAvalados = array();
    
    if(isset($resultPartidoAvalados)) {
        foreach ($resultPartidoAvalados as $row) {
            for ($i = 0; $i < count($votosPartido); $i++) {
                if($row['codpartido'] == $votosPartido[$i]['codpartido'] ){
                    $votosPartido[$i]['avalados'] =  $row['avalados'];
                    break;
                }
            }
        }
    }
    
    //Asigno el numero de candidatos elegidos al partido
    $sqlite->query($queryPartidoElegidos);
    $resultPartidoElegidos = $sqlite->returnRows();
    $arrElegidos = array();
    
    if(isset($resultPartidoElegidos)) {
        foreach($resultPartidoElegidos as $row) {
            for ($i = 0; $i < count($votosPartido); $i++) {
                if ($row['codpartido'] == $votosPartido[$i]['codpartido'] ) {
                    $votosPartido[$i]['elegidos'] =  $row['elegidos'];
                    break;
                }
            }
        }
    }
    
    //Obtener la corporacion y el potencial
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $corporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = utf8_encode($resulCorporacion[0]['descripcion']);
    //Cuando es comuna y cuando es mesa
    
    
    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    
    $queryDivipoles = getQueryDivipolCompleta($coddivipol,$codnivel);
    
    $nmDepartamento = "";
    $nmMunicipio = "";
    $nmZona = "";
    $nmPueto = "";
    $nmComuna = "";
    
    $sqlite->query($queryDivipoles);
    $resultDivipol = $sqlite->returnRows();
    
    if (isset($resultDivipol)) {
        foreach ($resultDivipol as $row) {
            $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
            switch($row['codnivel']) {
                case 1:
                    $nmDepartamento = utf8_encode($row['descripcion']);
                    break;
                case 2:
                    $nmMunicipio = utf8_encode($row['descripcion']);
                    break;
                case 3:
                    $nmZona = utf8_encode($row['descripcion']);
                    break;
                case 4:
                    $nmPueto = utf8_encode($row['descripcion']);
                    break;
            }
        }
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['idcomuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = utf8_encode($resultDivipol[0]['descripcion']);
        $nmZona = ""; 
    }
    
    //Cierro la coneccion a la base de datos
    $sqlite->close(); 
    unset($sqlite)
    
?>