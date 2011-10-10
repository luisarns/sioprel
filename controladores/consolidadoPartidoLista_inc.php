<?php

	$codcorpo    = $datos->codcorporacion;
	$nivcorpo    = $datos->corpnivel;
	$codcordiv   = substr($datos->coddivipol,0,getNumDigitos($nivcorpo));
	$coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	
	$texto1 = "";
	$texto2 = "";
	$texto3 = "";
	
	if(isset($datos->codpartido)){
		$texto3 = "AND pp.codpartido = $datos->codpartido ";
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
	GROUP BY pp.codpartido, pp.descripcion
	ORDER BY votos DESC;
EOF;
	
	//Query para traer los los candidatos cuando la opcion detallado esta activada
	// $query1 =<<<EOR
	// SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
	// FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv
	// WHERE pp.codpartido = pc.codpartido $texto1
	// AND pm.codtransmision = mv.codtransmision $texto2
	// AND pc.idcandidato = mv.idcandidato $texto3
	// AND pc.coddivipol LIKE '$codcordiv' || '%'
	// AND pm.coddivipol LIKE '$coddivcorto' || '%'
	// AND pc.codnivel = $nivcorpo
	// AND pm.codcorporacion = $codcorpo
	// GROUP BY pp.codpartido, pp.descripcion
	// ORDER BY votos DESC;
// EOR;
	
	//Hacer la consulta 2 para estraer los candidatos por partido, junto con el consolidado de la votacion obtenida por
	//cada candidato
	
	// echo $query;
	
	//Hacer una union de las dos consultas para cuando detallado es seleccionado
	//Hacer la consulta de los candidatos cuando detallado esta seleccionado
	
	$result   = ibase_query($firebird,$query);

?>
