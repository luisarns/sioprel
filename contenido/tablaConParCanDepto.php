<?php
    require('conexion.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParCanDepto.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";
    
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    $codpartido = $_GET['codpartido'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = "";
    if ($_GET['comuna'] !='-') {
        $txt = " AND pc.idcomuna = " . $_GET['comuna'];
    }
    
    $nivcorpo = getNivelCorporacion($corporacion);
    $coddivcorpo = str_pad(substr($coddivipol, 0, getNumDigitos($nivcorpo)),9,'0');
    
    
    $queryInscritos = <<<PAV
        SELECT pc.codcandidato, pc.nombres, pc.apellidos , pc.elegido
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pp.codpartido = $codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        $txt
        ORDER BY pc.elegido
PAV;
    
    $firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $resultInscritos  = ibase_query($firebird,$queryInscritos);
   
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
            <strong>Ciudadanos Inscritos y Elegidos</strong>
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
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Elegido</th>
    </tr>
    <?php while ($row = ibase_fetch_object($resultInscritos)) { ?>
            <tr>
                <td><?php echo str_pad($row->CODCANDIDATO, 3, '0', STR_PAD_LEFT)?></td>
                <td><?php echo htmlentities($row->NOMBRES)?></td>
                <td><?php echo htmlentities($row->APELLIDOS)?></td>
                <td><?php echo ($row->ELEGIDO != '0')? 'SI' : 'NO' ; ?></td>
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
    ibase_free_result($resultInscritos);
    ibase_close($firebird);
?>
