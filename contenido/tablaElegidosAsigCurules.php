<?php
    require('conexionSQlite3.php');
    include_once('FunDivipol.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repElegidosAsigCurules.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";

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
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
        $filtroComuna = "AND idcomuna = " . $_GET['comuna'];
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
    
    //Agregar la consulta para obtener la cifra repartidora y el cociente
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
    
    $nocurules = $resultCurules[0]['NUMCURULES'];
    $cuociente = $resultCurules[0]['CUOCIENTE'];
    $cifrarepartidora = $resultCurules[0]['CIFRAREPARTIDORA'];
    
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
	

    <table width="100%" align="center" border="1" cellspacing="3" cellpadding="0" class="regSuaveLeft">
         <tr>
            <td><strong>No. Curules</strong></td>
            <td><?php echo number_format($nocurules)?></td>
        </tr>
        <tr>
            <td><strong>Cociente</strong></td>
            <td><?php echo number_format($cuociente) ?></td>
        </tr>
        <tr>
            <td><strong>Cifra Repartidora</strong></td>
            <td><?php echo number_format($cifrarepartidora) ?></td>
        </tr>
    </table>

    <!-- Inicio codigo estilo de tabla-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
                <td width="5%" background="../images/ds_comp_bars_gral.jpg">
                <img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
                </td>
                <td width="83%" background="../images/ds_comp_bars_gral.jpg">
                    <strong>Elegidos Asignaci&oacute;n Curules</strong>
                </td>
                <td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
                        <img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
                </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
            <td class="regOscuro" align="left">
                    <strong>&nbsp;</strong>
            </td>
    </tr>
    </table>

    <table width="100%" border="0" cellspacing="3" cellpadding="0" class="regSuaveRultados">
        <tr>
            <th>C&oacute;digo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Partido</th>
            <th class="numero">Votos</th>
        </tr>
        <?php if (isset($result)) { ?>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?php echo str_pad($row['codpartido'],3,'0',STR_PAD_LEFT) . '-' . str_pad($row['codcandidato'],3,'0',STR_PAD_LEFT) ?></td>
                    <td><?php echo htmlentities($row['nombres'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                    <td><?php echo htmlentities($row['apellidos'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                    <td><?php echo htmlentities($row['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                    <td class="numero"><?php echo number_format($row['votos'])?></td>
                </tr>
            <?php } ?>
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
