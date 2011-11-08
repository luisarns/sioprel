<?php
	require('conexion.php');
	include_once('FunDivipol.php');
	
	$urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repResVotaCandidato.php" . $_SERVER['REQUEST_URI'];
	
	$codcorporacion = $_GET['corporacion'];
	$coddepto = $_GET['departamento'];
	$codmunip = $_GET['municipio'];
	
	$txt = "";
	if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
		$txt = "AND pc.idcomuna = " .$_GET['comuna'];
		$txt .= " AND pd.idcomuna = " .$_GET['comuna'];
	}
	
	$urlReportes .= "&formato=";
	
	$codcordivi = $coddepto . "" . $codmunip;
	$nivcorpo = getNivelCorporacion($codcorporacion);
	$cordivi = substr($codcordivi, 0, getNumDigitos($nivcorpo));
	
	$query =<<<EOF
	SELECT lpad(pc.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo  ,pc.nombres, 
	pc.apellidos ,sum(mv.numvotos) as votos
	FROM pmesas pm, mvotos mv, pcandidatos pc, pdivipol pd
	WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
	AND pd.coddivipol LIKE '$codcordivi' || '%' $txt
	AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
	AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
	AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
	AND pm.codcorporacion = $codcorporacion
	GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
	ORDER BY pc.codpartido, pc.codcandidato
EOF;

	$firebird = ibase_connect($host, $username, $password) or die("No se pudo conectar a la base de datos: " . ibase_errmsg());
	$result   = ibase_query($firebird, $query);
	
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
			<strong>Resumen Votaci&oacute;n Candidatos </strong>
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
		<th>Votos</th>
	</tr>
	<?php while($row = ibase_fetch_object($result)) { ?>
		<tr>
			<td><?php echo $row->CODIGO?></td>
			<td><?php echo htmlentities($row->NOMBRES)?></td>
			<td><?php echo htmlentities($row->APELLIDOS)?></td>
			<td><?php echo number_format($row->VOTOS)?></td>
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
