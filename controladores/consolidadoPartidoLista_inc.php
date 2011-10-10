<?php

	$codcorpo    = $datos->codcorporacion;
	$nivcorpo    = $datos->corpnivel;
	$codcordiv   = substr($datos->coddivipol,0,getNumDigitos($nivcorpo));
	$coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	
	$texto1 = "";
	$texto2 = "";
	$texto3 = "";
	$txt4 = "";
	
	if(isset($datos->codpartido)){
		$texto3 = "AND pp.codpartido = $datos->codpartido ";
		$txt4 = "AND pc.codpartido = $datos->codpartido ";
	}
	if(isset($datos->idmesa)){
		$texto1 = "AND pm.codtransmision = '$datos->codtransmision' ";
	}
	if(isset($datos->idcomuna)){
		$texto2 = "AND pc.idcomuna = $datos->idcomuna ";
	}
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query =<<<EOF
	SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
	FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv
	WHERE pp.codpartido = pc.codpartido $texto1
	AND pm.codtransmision = mv.codtransmision $texto2
	AND pc.idcandidato = mv.idcandidato $texto3
	AND pc.coddivipol LIKE '$codcordiv' || '%'
	AND pm.coddivipol LIKE '$coddivcorto' || '%'
	AND pc.codnivel = $nivcorpo
	AND pm.codcorporacion = $codcorpo
	GROUP BY pp.codpartido, pp.descripcion;
EOF;
	
	$result1 = null;
	$query1 = null;
	if(isset($datos->detallado)){
		$query1 =<<<EOR
		SELECT pc.codpartido,pc.codcandidato, pc.nombres || ' ' || pc.apellidos as descripcion, SUM(mv.numvotos) as votos
		FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
		WHERE pm.codtransmision = mv.codtransmision $texto1
		AND pc.idcandidato = mv.idcandidato $texto2
		AND pc.coddivipol LIKE '$codcordiv' || '%'
		AND pm.coddivipol LIKE '$coddivcorto'  || '%'
		AND pm.codcorporacion = $codcorpo $txt4
		AND pc.codnivel = $nivcorpo
		GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
		
		$result1 = ibase_query($firebird,$query1);
	}
	$result   = ibase_query($firebird,$query);

?>
