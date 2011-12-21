<?php
    require('conexionSQlite3.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParDepto.php" . $_SERVER['REQUEST_URI'];
    $urlDetalles = "http://" . $_SERVER['HTTP_HOST'] . "/contenido/tablaConParCanDepto.php" . $_SERVER['REQUEST_URI'];
    
    $urlReportes .= "&formato=";
    $urlDetalles .= "&codpartido=";
    
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt1 = "";
    $filtroComuna = "";
    if ($_GET['comuna'] !='-') {
        $filtroComuna = " AND idcomuna = " . $_GET['comuna'];
        $txt1 = " AND pc.idcomuna = " . $_GET['comuna'];
    }
    
    $nivcorpo = getNivelCorporacion($corporacion);
    $codcorpordivipol = substr($coddivipol, 0, getNumDigitos($nivcorpo));
    $coddivcorpo = str_pad(substr($coddivipol, 0, getNumDigitos($nivcorpo)),9,'0');
    
    $queryVotacionPartido =<<<VTP
    SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, sum(dd.numvotos) as votos
    FROM PPARTIDOS pp, 
         ( SELECT codpartido,idcandidato 
           FROM PCANDIDATOS 
           WHERE codcorporacion = $corporacion
           AND coddivipol LIKE '$codcorpordivipol' || '%'
           AND codnivel = $nivcorpo $filtroComuna ) pc,
         ( SELECT * 
           FROM DDETALLEBOLETIN 
           WHERE coddivipol LIKE '$coddivipol' || '%' 
           AND codnivel = $codnivel AND codcorporacion = $corporacion $filtroComuna ) dd
    WHERE pp.codpartido = pc.codpartido AND pc.idcandidato = dd.idcandidato
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
VTP;
    
//    echo "<br/>" . $queryVotacionPartido . "<br/>";
    
    $queryPartidoAvalados = <<<PAV
        SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, count(pc.idcandidato) as avalados
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        $txt1
        GROUP BY pp.codpartido, pp.descripcion
PAV;
    
//    echo "<br/>" . $queryPartidoAvalados . "<br/>";
    
    $queryPartidoElegidos = <<<PEL
        SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, count(pc.idcandidato) as elegidos
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
    
//    echo "<br/>" . $queryPartidoElegidos . "<br/>";
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($queryVotacionPartido);
    $resultVotacionPartido = $sqlite->returnRows();
//    $sqlite->close();
    
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
        
    $sqlite->close(); 
    unset($sqlite);
    
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
        <th class="numero">Cand.Avalados</th>
        <th class="numero">Cand.Elegidos</th>
        <th class="numero">Votos</th>
    </tr>
    <?php foreach($votosPartido as $votoPartido) { ?>
            <tr onclick="cargarDetalle('<?php echo $urlDetalles.$votoPartido['codpartido']; ?>')">
                <td><?php echo str_pad($votoPartido['codpartido'], 3, '0', STR_PAD_LEFT)?></td>
                <td><a href="#"><?php echo htmlentities($votoPartido['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></a></td>
                <td class="numero"><?php echo number_format($votoPartido['avalados'])?></td>
                <td class="numero"><?php echo number_format($votoPartido['elegidos'])?></td>
                <td class="numero"><?php echo number_format($votoPartido['votos'])?></td>
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

