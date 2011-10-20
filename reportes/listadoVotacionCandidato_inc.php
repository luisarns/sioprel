<?php

	$codcorporacion = $_GET['corporacion'];
	$nivcorpo  = getNivelCorporacion($codcorporacion);
	
	$depto = $_GET['departamento'];
	$muncp = ($_GET['municipio']!="-")?$_GET['municipio']:"";
	
	$coddivcorto = $depto.$muncp;
	$codcordiv   = substr($coddivcorto,0,getNumDigitos($nivcorpo));
	
	$txt = "";
	if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
		$txt = "AND pc.idcomuna = ".$_GET['comuna'];
		$txt .= " AND pd.idcomuna = ".$_GET['comuna'];
	}
	
	$urlReportes .="&formato=";
	
	$query =<<<EOF
	SELECT pp.codpartido ||'-'|| pc.codcandidato as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
	FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
	WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
	AND pd.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
	AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0
	AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $txt
	GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);
	
?>