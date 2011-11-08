<?php
    require('conexion.php');
    include_once('FunDivipol.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConPartidolista.php?";

    $codcorporacion = $_GET['corporacion'];
    $nivcorpo  = getNivelCorporacion($codcorporacion);

    $urlReportes.="codcorporacion=$codcorporacion";

    $coddivipol = $_GET['departamento'];
    $codnivel   = 1;


    if (isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
        $coddivipol .= $_GET['municipio'];
        $codnivel = 2;

        if(isset($_GET['zona']) && $_GET['zona'] != "-" ){
                $coddivipol .= $_GET['zona'];
                $codnivel = 3;
        }

        if(isset($_GET['puesto']) && $_GET['puesto'] !="-"){
                $coddivipol = $_GET['puesto'];
                $codnivel = 4;
        }
    }

    $urlReportes.="&nivcorpo=$nivcorpo&coddivipol=$coddivipol&codnivel=$codnivel&opcion=1";

    $codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));

    $texto1 = " ";
    if(isset($_GET['mesa']) && $_GET['mesa'] != "-"){
        $texto1 = " AND pm.codtransmision = '".$_GET['mesa']."'";
        $urlReportes.="&codtransmision=".$_GET['mesa'];
    }

    $texto2 ="";
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
        $texto2 = " AND pc.idcomuna = ".$_GET['comuna'];
        $texto2 .= " AND pd.idcomuna = ".$_GET['comuna'];
        $urlReportes.="&idcomuna=".$_GET['comuna'];
    }

    $texto3 = "";
    $txt4 = "";
    if(isset($_GET['partido']) && $_GET['partido'] != "-"){
        $texto3 = " AND pp.codpartido = ".$_GET['partido'];
        $txt4 = "AND pc.codpartido = ".$_GET['partido'];
        $urlReportes.="&codpartido=".$_GET['partido'];
    }

    $query =<<<EOF
    SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
    FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv, pdivipol pd
    WHERE pp.codpartido = pc.codpartido $texto1
    AND pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4
    AND pm.coddivipol = pd.coddivipol
    AND pm.codtransmision = mv.codtransmision $texto2
    AND pc.idcandidato = mv.idcandidato $texto3
    AND pc.coddivipol LIKE '$codcordiv'  || '%'
    AND pc.codnivel = $nivcorpo
    AND pm.codcorporacion = $codcorporacion
    GROUP BY pp.codpartido, pp.descripcion
EOF;


    $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $result   = ibase_query($firebird,$query);

    $result1 = null;
    $query1 = null;
    if (isset($_GET['detallado']) && $_GET['detallado'] == 1) {
            $query1 =<<<EOR
            SELECT pc.codpartido,pc.codcandidato, pc.nombres ||' '|| CASE WHEN pc.codcandidato = 0 
            THEN '(LISTA)' ELSE pc.apellidos END as descripcion, SUM(mv.numvotos) as votos
            FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
            WHERE pm.codtransmision = mv.codtransmision $texto1
            AND pc.idcandidato = mv.idcandidato $texto2
            AND pc.coddivipol LIKE '$codcordiv' || '%'
            AND pm.coddivipol LIKE '$coddivipol'  || '%'
            AND pm.codcorporacion = $codcorporacion $txt4
            AND pc.codnivel = $nivcorpo
            GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
            $result1 = ibase_query($firebird,$query1);
            $urlReportes.="&detallado=".$_GET['detallado'];
    }

    $urlReportes.="&formato=";

    $candidatos = array();
    if($result1 != null){
        while($row = ibase_fetch_object($result1)) {
            array_push($candidatos,$row);
        }
    }
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
                <strong>Consolidado Partido Lista </strong>
        </td>
        <td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
                <img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
        </td>
    </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td class="regOscuro" align="left"><STRONG>&nbsp;</STRONG></td></tr>
</table>

<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuaveRultados">
	<tr>
		<th>C&oacute;digo</th>
		<th>Nombre</th>
		<th>Votos</th>
	</tr>
	<?php while($row = ibase_fetch_object($result)) { ?>
		<tr>
			<td><?php echo str_pad($row->CODIGO, 3, '0', STR_PAD_LEFT)?></td>
			<td><?php echo htmlentities($row->DESCRIPCION)?></td>
			<td><?php echo number_format($row->VOTOS)?></td>
			
		</tr>
		<?php 
			foreach($candidatos as $candidato) { 
				if($candidato->CODPARTIDO == $row->CODIGO) { ?>
					<tr>
					<td><?php echo str_pad($row->CODIGO, 3, '0', STR_PAD_LEFT) . '-' . str_pad($candidato->CODCANDIDATO, 3, '0', STR_PAD_LEFT) ?></td>
					<td><?php echo htmlentities($candidato->DESCRIPCION)?></td>
					<td><?php echo number_format($candidato->VOTOS)?></td>
					</tr>
		<?php }} ?>
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
	if ($result1 != null) {
            ibase_free_result($result1);
        }
	ibase_close($firebird);
?>
