<?php
    require('conexion.php');
    include_once('FunDivipol.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repLisElegCorp.php" . $_SERVER['REQUEST_URI'];

    $codcorporacion = $_GET['corporacion'];
    $coddivipol = $_GET['departamento'];
    $codnivel   = 1;

    if (isset($_GET['municipio']) && $_GET['municipio'] != "-" ) {
            $coddivipol .= $_GET['municipio'];
            $codnivel = 2;
    }

    $tx1 = "";
    if ($_GET['sexo'] != "-") {
            $tx1 = "AND pc.genero='" . $_GET['sexo'] . "'";
    }

    $tx2 = "";
    if ($_GET['partido'] != "-") {
            $tx2 = "AND pc.codpartido=" . $_GET['partido'];
    }

    $tx3 = "";
    if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
            $tx3 = "AND pc.idcomuna = " . $_GET['comuna'];
    }

    $urlReportes .= "&formato=";

    $nivcorpo = getNivelCorporacion($codcorporacion);
    $cordivi = substr($coddivipol, 0, getNumDigitos($nivcorpo));

    $query =<<<EOF
	SELECT lpad(pp.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
	FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
	WHERE pc.coddivipol LIKE '$cordivi'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
	AND pd.coddivipol   LIKE '$coddivipol' || '%' AND pm.codtransmision = mv.codtransmision
	AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0 $tx1
	AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $tx2
	AND pm.codcorporacion = $codcorporacion $tx3
        AND pc.elegido <> '0'
	GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;
    
    $firebird = ibase_connect($host, $username, $password) 
            or die("No se pudo conectar a la base de datos: " . ibase_errmsg());
    $result = ibase_query($firebird, $query);
    
?>

<table>
    <tr>
        <td><a href="<?php echo $urlReportes . "pdf"?>" target="_BLANK"><img src="images/logo_pdf.png"  alt="pdf" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes . "xls"?>" target="_BLANK"><img src="images/logo_xls.jpg"  alt="xls" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes . "doc"?>" target="_BLANK"><img src="images/logo_doc.jpg"  alt="doc" height="20" width="20" /></a><td>
        <td><a href="<?php echo $urlReportes . "txt"?>" target="_BLANK"><img src="images/logo_text.jpg" alt="txt" height="20" width="20" /></a><td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td width="5%" background="../images/ds_comp_bars_gral.jpg">
        <img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
        </td>
        <td width="83%" background="../images/ds_comp_bars_gral.jpg">
                <strong>Listado Elegidos Corporaci&oacute;n </strong>
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
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Partido</th>
        <th class="numero">Votos</th>
    </tr>
    <?php while($row = ibase_fetch_object($result)) { ?>
            <tr>
                <td><?php echo $row->CODIGO ?></td>
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
	ibase_free_result($result);
	ibase_close($firebird);
?>
