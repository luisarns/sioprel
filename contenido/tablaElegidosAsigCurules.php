<?php
    require('conexion.php');
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

    $txt  = "";
    $txt1 = "";
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
        $txt = "AND pc.idcomuna = " . $_GET['comuna'];
        $txt .= " AND pd.idcomuna = " . $_GET['comuna'];
        $txt1 = "AND idcomuna = ". $_GET['comuna'];
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
    
    //Agregar la consulta para obtener la cifra repartidora y el cociente
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
        <?php while($row = ibase_fetch_object($result)) { ?>
            <tr>
                <td><?php echo $row->CODIGO?></td>
                <td><?php echo htmlentities($row->NOMBRES)?></td>
                <td><?php echo htmlentities($row->APELLIDOS)?></td>
                <td><?php echo htmlentities($row->DESCRIPCION)?></td>
                <td class="numero"><?php echo number_format($row->VOTOS)?></td>
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
	
<?php
    
    ibase_free_result($resultCurules);
    ibase_free_result($result);
    ibase_close($firebird);
?>