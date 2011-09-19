<?php 
	/** 
	* Este archivo recibe un objeto de nombre $datos cuyas propiedades son los filtros
	* y retorna un resource $result (con el resultado de la consulta )
	*/
	$coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	$corpdesc = $datos->descripcion;
	$texto1 = "";
	$texto2 = "";
	if(isset($datos->codTransmision)){
		$texto1 = "AND pm.codtransmision = '$datos->codTransmision'";
	}
	if(isset($datos->idcomuna)){
		$texto2 = "AND pc.idcomuna = $datos->idcomuna";
	}
	
	$query =<<<EOF
	SELECT '$corpdesc' divipol ,pp.codpartido as codigo, pp.descripcion as partido, sum(mv.numvotos) as votos
	FROM ppartidos pp, pmesas pm, pcandidatos pc, mvotos mv
	WHERE pp.codpartido = pc.codpartido $texto2
	AND pm.coddivipol LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
	AND pc.idcandidato = mv.idcandidato $texto1
	GROUP BY pp.codpartido,pp.descripcion ORDER BY votos DESC
EOF;
	
	//echo $query;
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);
	
?>
