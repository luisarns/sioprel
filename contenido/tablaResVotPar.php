<?php 
    require('conexionSQlite3.php');
    include_once('FunDivipol.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repResVotaPartido.php".$_SERVER['REQUEST_URI'];

    $coddepto = $_GET['departamento'];
    $codmunip = ($_GET['municipio'] != "-")? $_GET['municipio'] : "" ;
    $codcordivi = $coddepto . $codmunip;

    $urlReportes .= "&formato=";

    $query =<<<EOF
    SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, sum(dd.numvotos) as votos
    FROM PPARTIDOS pp, PCANDIDATOS  pc,       
     ( SELECT idcandidato,numvotos
       FROM DDETALLEBOLETIN
    WHERE coddivipol LIKE '$codcordivi' || '%' ) dd 
    WHERE pp.codpartido = pc.codpartido AND pc.idcandidato = dd.idcandidato
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EOF;
	
    // echo "<br/>" . $query . "<br/>";
	
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
			<strong>Resumen Votaci&oacute;n Partido</strong>
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

<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuaveRultados">
    <tr>
        <th>C&oacute;digo</th>
        <th>Descripci&oacute;n</th>
        <th class="numero">Votos</th>
    </tr>
    <?php if (isset($result)) { ?>
        <?php foreach($result as $row) { ?>
            <tr>
                <td><?php echo str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT)?></td>
                <td><?php echo htmlentities($row['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                <td class="numero"><?php echo number_format($row['votos'])?></td>
            </tr>
        <?php } ?>
    <?php } ?>  
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td class="regOscuro" align="left">
            <strong>&nbsp;</strong>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td background="../images/ds_comp_bari_gral.jpg">
			<img src="../images/ds_comp_izq_bari_gral.jpg" width="25" height="25">
		</td>
		<td background="../images/ds_comp_bari_gral.jpg">&nbsp;</td>
		<td align="right" background="../images/ds_comp_bari_gral.jpg" >
			<img src="../images/ds_comp_der_bari_gral.jpg" width="25" height="25">
		</td>
	</tr>
</table>

