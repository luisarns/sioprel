<?php
    require('conexionSQlite3.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repResCurAsignadas.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";

    $codcorporacion = $_GET['corporacion'];
    $coddivcorto = $_GET['departamento'];
    $codnivel = 1;

    if (isset($_GET['municipio'])&& $_GET['municipio'] != '-') {
        $coddivcorto .= $_GET['municipio'];
        $codnivel = $codnivel + 1;
    }

    $coddivipol = str_pad($coddivcorto,9,'0');

    $txt = "";
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
            $txt = "AND pcp.idcomuna = ".$_GET['comuna'];
    }
	
    $query =<<<EOF
        SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, pcp.numcurules as numcurules, pcp.totalvotos as totalvotos
        FROM ppartidos pp, pcurulespartidos pcp
        WHERE pp.codpartido = pcp.codpartido 
        AND pcp.coddivipol = '$coddivipol'
        AND pcp.codnivel = $codnivel
        AND pcp.codcorporacion = $codcorporacion
        $txt
        ORDER BY pcp.numcurules DESC
EOF;
    
    echo "<br/>" . $query . "<br/>";
    

    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();
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
		<font size="2"><strong>Resumen Curules Asignadas</strong></font>
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

<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave">
	<tr>
            <th>C&oacute;digo</th>
            <th>Partido</th>
            <th class="numero">No. Curules</th>
            <th class="numero">Votos</th>
	</tr>
        <?php if (isset($result)) { ?>
            <?php foreach($result as $row) { ?>
                    <tr>
                        <td><?php echo str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td><?php echo htmlentities($row['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                        <td class="numero"><?php echo number_format($row['numcurules'])?></td>
                        <td class="numero"><?php echo number_format($row['totalvotos'])?></td>
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
