<?php 
	/** 
	* Este archivo recibe un objeto de nombre $datos cuyas propiedades son los filtros
	* y retorna a arreglo asociativo $rows (con el resultado de la consulta ) y 
	* $numRows con el numero de registros devueltos por la consulta
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
	
	$sqlite = new SPSQLite(PATH_DB.'elecciones2011.db');
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	$sqlite->close();
	unset($sqlite);
?>
