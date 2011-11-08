<?php
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repLisMayorVota.php" . $_SERVER['REQUEST_URI'];
		$urlReportes .= "&formato=";
		
		
		$codcorporacion = $_GET['corporacion'];
		$nivcorpo  = getNivelCorporacion($codcorporacion);
		
		$depto = $_GET['departamento'];
		$muncp = ($_GET['municipio'] != "-")? $_GET['municipio'] : "";
		
		$coddivcorto = $depto . $muncp;
		$codcordiv   = substr($coddivcorto, 0, getNumDigitos($nivcorpo));
		
		$txt = "";
		if (isset($_GET['comuna']) && $_GET['comuna'] != "-") {
			$txt = "AND pc.idcomuna = " . $_GET['comuna'];
			$txt .= " AND pd.idcomuna = " . $_GET['comuna'];
		}
		
		$query =<<<EOF
		SELECT pc.nombres as descripcion, SUM(mv.numvotos) as votos
		FROM pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
		WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
		AND pd.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
		AND pc.idcandidato = mv.idcandidato AND pc.codcandidato = 0
		AND pm.codcorporacion = $codcorporacion
		AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $txt
		GROUP BY pc.nombres
		ORDER BY votos DESC
EOF;
		
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: " . ibase_errmsg());
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
	
	<!-- Inicio codigo estilo de tabla-->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="5%" background="../images/ds_comp_bars_gral.jpg">
		<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
		</td>
		<td width="83%" background="../images/ds_comp_bars_gral.jpg">
			<strong>Listas Mayor Votaci&oacute;n</strong>
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
	
	<table width="100%" border="0" cellspacing="3" cellpadding="0" class="regSuaveRultados">
            <tr>
                <th>Lista</th>
                <th>Votos</th>
            </tr>
            <?php while($row = ibase_fetch_object($result)) { ?>
                <tr>
                    <td><?php echo htmlentities($row->DESCRIPCION)?></td>
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
	<!-- Fin codigo estilo de tabla-->
	
	<?php 
		ibase_free_result($result);
		ibase_close($firebird);
	?>