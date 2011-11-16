<?php
    require('conexion.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParDepto.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";
    
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = ""; //Filtro para la comuna
    $txt1 = "";
    if ($_GET['comuna'] !='-') {
        $txt = " AND pd.idcomuna = " . $_GET['comuna'];
        $txt .= " AND pc.idcomuna = " . $_GET['comuna'];
        $txt1 = " AND pc.idcomuna = " . $_GET['comuna'];
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
    
    echo $queryVotacionPartido;
    echo "<br/><br/>";
    echo $queryPartidoAvalados;
    echo "<br/><br/>";
    echo $queryPartidoElegidos;
    
    
    
?>