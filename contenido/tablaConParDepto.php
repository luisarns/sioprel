<?php
    require('conexion.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParDepto.php" . $_SERVER['REQUEST_URI'];
    $urlDetalles = "http://" . $_SERVER['HTTP_HOST'] . "/contenido/tablaConParCanDepto.php" . $_SERVER['REQUEST_URI'];
    
    $urlReportes .= "&formato=";
    $urlDetalles .= "&codpartido="; //la url para mostrar la informacion detallada de los candidatos por partidos
    
    
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
    
    ibase_free_result($resultPartidoElegidos);
    ibase_free_result($resultPartidoAvalados);
    ibase_free_result($resultVotacionPartido);
    ibase_close($firebird);
?>

<table>
    <tr>
        <td><a href="<?php echo $urlReportes."pdf"?>" target="_BLANK"><img src="images/logo_pdf.png"  alt="pdf" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes."xls"?>" target="_BLANK"><img src="images/logo_xls.jpg"  alt="xls" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes."doc"?>" target="_BLANK"><img src="images/logo_doc.jpg"  alt="doc" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes."txt"?>" target="_BLANK"><img src="images/logo_text.jpg" alt="txt" height="20" width="20" /></a><td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td width="5%" background="../images/ds_comp_bars_gral.jpg">
            <img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
        </td>
        <td width="83%" background="../images/ds_comp_bars_gral.jpg">
            <strong>Consolidado Partido Departamental</strong>
        </td>
        <td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
            <img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td class="regOscuro" align="left">
            <STRONG>&nbsp;</STRONG>
        </td>
    </tr>
</table>

<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuaveRultados">
    <tr>
        <th>C&oacute;digo</th>
        <th>Partido</th>
        <th>Cand.Avalados</th>
        <th>Cand.Elegidos</th>
        <th>Votos</th>
    </tr>
    <?php foreach($votosPartido as $votoPartido) { ?>
            <tr onclick="cargarDetalle('<?php echo $urlDetalles.$votoPartido['codpartido']; ?>')">
                <td><?php echo str_pad($votoPartido['codpartido'], 3, '0', STR_PAD_LEFT)?></td>
                <td><a href="#"><?php echo htmlentities($votoPartido['descripcion'])?></a></td>
                <td><?php echo number_format($votoPartido['avalados'])?></td>
                <td><?php echo number_format($votoPartido['elegidos'])?></td>
                <td><?php echo number_format($votoPartido['votos'])?></td>
            </tr>
    <?php } ?>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td class="regOscuro" align="left">
            <STRONG>&nbsp;</STRONG>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td background="../images/ds_comp_bari_gral.jpg">
            <img src="../images/ds_comp_izq_bari_gral.jpg" width="25" height="25">
        </td>
        <td background="../images/ds_comp_bari_gral.jpg">&nbsp;</td>
        <td align="right" background="../images/ds_comp_bari_gral.jpg">
            <img src="../images/ds_comp_der_bari_gral.jpg" width="25" height="25">
        </td>		
    </tr>
</table>

<div id="tablaConParCanDepto">
    <!-- Aqui se renderiza la tabla con el detalle de los candidatos por partidos-->
</div>

