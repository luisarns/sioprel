<?php
    require_once('conexionSQlite3.php');
    include_once('FunDivipol.php');

    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConPartidolista.php?";

    $codcorporacion = $_GET['corporacion'];
    $nivcorpo  = getNivelCorporacion($codcorporacion);

    $urlReportes .= "codcorporacion=$codcorporacion";

    $coddivipol = $_GET['departamento'];
    $codnivel   = 1;
    
    if (isset($_GET['municipio']) && $_GET['municipio'] != "-" ) {
        $coddivipol .= $_GET['municipio'];
        $codnivel = 2;

        if (isset($_GET['zona']) && $_GET['zona'] != "-") {
                $coddivipol .= $_GET['zona'];
                $codnivel = 3;
        }
        
        $hayPuesto = false;
        if(isset($_GET['puesto']) && $_GET['puesto'] != "-"){
                $coddivipol = $_GET['puesto'];
                $codnivel = 4;
                $hayPuesto = true;
        }
    }

    $urlReportes.="&nivcorpo=$nivcorpo&coddivipol=$coddivipol&codnivel=$codnivel&opcion=1";
    $codcordiv = substr($coddivipol, 0, getNumDigitos($nivcorpo));
    
    $hayMesa = false;
    $filtroMesa = "";
    if(isset($_GET['mesa']) && $_GET['mesa'] != "-"){
        $filtroMesa = " AND codtransmision = '" . $_GET['mesa'] . "'";
        $urlReportes .= "&codtransmision=" . $_GET['mesa'];
        $hayMesa = true;
    }

    $filtroComuna = "";
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
        $filtroComuna = " AND idcomuna = ".$_GET['comuna'];
        $urlReportes.="&idcomuna=".$_GET['comuna'];
        $hayComuna = true;
    }

    $filtroPartido = "";
    if(isset($_GET['partido']) && $_GET['partido'] != "-"){
        $filtroPartido = "AND pp.codpartido = ".$_GET['partido'];
        $urlReportes.="&codpartido=".$_GET['partido'];   
    }
   
    $query =<<<EOF
    SELECT pp.codpartido as codigo ,pp.descripcion as descripcion, SUM(mv.numvotos) as votos
    FROM PPARTIDOS pp,
     ( SELECT coddivipol, codnivel 
       FROM PDIVIPOL 
       WHERE coddivipol LIKE '$coddivipol' || '%' AND codnivel = 4 $filtroComuna ) pd,
     ( SELECT codpartido,idcandidato
       FROM PCANDIDATOS 
       WHERE coddivipol LIKE '$codcordiv' || '%' AND codnivel = $nivcorpo $filtroComuna ) pc,
     ( SELECT codtransmision,coddivipol
       FROM PMESAS 
       WHERE codcorporacion = $codcorporacion $filtroMesa ) pm,
    MVOTOS mv
    WHERE pp.codpartido = pc.codpartido AND pd.coddivipol = pm.coddivipol $filtroPartido
    AND pm.codtransmision = mv.codtransmision AND mv.idcandidato = pc.idcandidato
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EOF;
    
//    echo "Consolidado Partido<br/>" . $query;
    
    if ($codnivel <= 2 ){
     $query =<<<EIF
     SELECT pp.codpartido as codigo ,pp.descripcion as descripcion, sum(dd.numvotos) as votos
     FROM  PPARTIDOS  pp,
     ( SELECT codpartido,idcandidato
       FROM PCANDIDATOS
       WHERE codcorporacion = $codcorporacion
       AND coddivipol LIKE '$codcordiv' || '%'
       AND codnivel = $nivcorpo $filtroComuna ) pc,
     ( SELECT * 
       FROM DDETALLEBOLETIN 
       WHERE coddivipol LIKE '$coddivipol' || '%' 
       AND codnivel = $codnivel AND codcorporacion = $codcorporacion $filtroComuna ) dd
    WHERE pp.codpartido = pc.codpartido AND pc.idcandidato = dd.idcandidato $filtroPartido
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EIF;
    }
    
//    echo "Consolidado Partido<br/>" . $query;
    
    $queryPotencial = <<<FEO
        SELECT potencialf ,potencialm 
        FROM pdivipol
        WHERE coddivipol LIKE '$coddivipol' || '%'
        AND codnivel = $codnivel 
FEO;
    
    if ($hayMesa) {
        $codtransmision = $_GET['mesa'];
        $queryPotencial = "
            SELECT numvotos as POTENCIALF, 0 POTENCIALM
            FROM ptiposmesas pt,pmesas pm
            WHERE pm.codtransmision = '$codtransmision'
            AND pm.codtipo = pt.codtipo";
    } else if ($hayComuna && !$hayPuesto) {
        $idcomuna = $_GET['comuna'];
        $queryPotencial = "
            SELECT sum(potencialf) as POTENCIALF,sum(potencialm) as POTENCIALM
            FROM pdivipol
            WHERE coddivipol LIKE '$coddivipol' || '%'
            AND codnivel = 4 
            AND idcomuna = $idcomuna
            GROUP BY codnivel";
    }
    
//    echo "<br/>Potencial<br/>" . $queryPotencial;
    
    $circunscripcion = ($codcorporacion != 5)? $nivcorpo : 3;
    
    $queryVotosEsp = <<<EOF
    SELECT pc.codtipovoto as codtipovoto ,pc.descripcion as descripcion, SUM(mv.numvotos) as votos
    FROM PTIPOSVOTOS pc,
     ( SELECT codtransmision,coddivipol
       FROM PMESAS 
       WHERE codcorporacion = $codcorporacion $filtroMesa ) pm,
     ( SELECT coddivipol, codnivel 
       FROM PDIVIPOL 
       WHERE coddivipol LIKE '$coddivipol' || '%' AND codnivel = 4 $filtroComuna ) pd,
    MVOTOSESPECIALES mv
    WHERE pm.coddivipol = pd.coddivipol AND pm.codtransmision = mv.codtransmision
    AND pc.codtipovoto = mv.codtipovoto AND mv.codcircunscripcion = '$circunscripcion'
    GROUP BY pc.codtipovoto,pc.descripcion 
    ORDER BY votos DESC
EOF;
    
//    echo "<br/>Votos Especiales<br/>" .  $queryVotosEsp;    
    
    if ($codnivel <= 2 ) {
     $queryVotosEsp =<<<EOF
     SELECT pc.codtipovoto as codtipovoto ,pc.descripcion as descripcion, SUM(de.numvotos) as votos
     FROM PTIPOSVOTOS pc,
     ( SELECT codtipovoto, numvotos 
       FROM DETALLEBOLETINESP 
       WHERE coddivipol LIKE '$coddivipol' || '%' AND codnivel = $codnivel 
       AND codcircunscripcion = '$circunscripcion'
       AND codcorporacion = $codcorporacion $filtroComuna ) de
    WHERE de.codtipovoto = pc.codtipovoto
    GROUP BY pc.codtipovoto,pc.descripcion 
    ORDER BY votos DESC
EOF;
    }
    
    
//    echo "<br/>Votos Especiales<br/>" .  $queryVotosEsp;
    
    //Desde aqui cambia el codigo para la coneccion
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($query);
    $result = $sqlite->returnRows();
   
    $sqlite->close();
    //Fin de la primera consulta
    
    //Consultas para obtener el potencial
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($queryPotencial);
    $row  = $sqlite->returnRows();
    $potencial = $row[0]['POTENCIALF'] + $row[0]['POTENCIALM'];
    
    $sqlite->close();
    //End Potencial
    
    //Votos especiales
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($queryVotosEsp);
    $resultVotosEsp  = $sqlite->returnRows();

    ///----------------///
    $sqlite->close();  ///
    unset($sqlite);    ///
    ///----------------///
    
    $totalVotos = 0;
    $partidos = array();
    
    if (isset($result)) {
        foreach($result as $row) {
            array_push($partidos,$row);
            $totalVotos += $row['votos'];
        }
    }

    $votacionEspecial = array();
    
    if (isset($resultVotosEsp)) {
        foreach($resultVotosEsp as $row) {
            array_push($votacionEspecial,$row);
            $totalVotos += $row['votos'];
        }
    }
    
//    echo "<br/> Total Votos : " . $totalVotos . "<br/>";
    
    $urlReportes.="&formato=";

    $candidatos = array();
    
    $participacion = round((($totalVotos*100)/$potencial),2);
    $asbtencion  = round(100 - $participacion,2);
    

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
        <td><strong>Potencial</strong></td>
        <td><?php echo number_format($potencial)?></td>
    </tr>
    <tr>
        <td><strong>Participaci&oacute;n</strong></td>
        <td><?php echo $participacion . '%'?></td>
    </tr>
    <tr>
        <td><strong>Abstenci&oacute;n</strong></td>
        <td><?php echo $asbtencion . '%'?></td>
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
		<th class="numero">Votos</th>
                <th class="numero">Participaci&oacute;n(%)</th>
	</tr>
        
	<?php foreach($partidos as $row) { ?>
                <tr>
                    <td><?php echo str_pad($row['codigo'], 3, '0', STR_PAD_LEFT)?></td>
                    <td><?php echo htmlentities($row['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></td>
                    <td class="numero"><?php echo number_format($row['votos'])?></td>
                    <td class="numero"><?php echo round($row['votos']*100/$potencial,2) . '%' ?></td>
                </tr>
	<?php } ?>
                
        <?php foreach ($votacionEspecial as $row ) { ?>
                <tr>
                    <td>&nbsp;</td>
                    <td><strong><?php echo htmlentities($row['descripcion'], ENT_QUOTES | ENT_IGNORE, "UTF-8")?></strong></td>
                    <td class="numero"><?php echo number_format($row['votos'])?></td>
                    <td class="numero"><?php echo round($row['votos']*100/$potencial,2) . '%' ?></td>
                </tr>
        <?php }?>
                
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
