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
	
	//Me trae todas las divipoles que corresponden a una comuna si es el caso
	$queryDivipol = "(SELECT coddivipol FROM pdivipol WHERE coddivipol LIKE $coddivcorto || '%' AND codnivel = 4 $texto2)";
	
	$query =<<<EOF
	SELECT '$corpdesc' divipol, co.descripcion as corporacion , pp.descripcion as descripcion, sum(mv.numvotos) as votos
	FROM ppartidos pp, pmesas pm, pcandidatos pc, mvotos mv, (SELECT * FROM pcorporaciones WHERE tipoeleccion = 1) co
	WHERE pp.codpartido = pc.codpartido
	AND pm.coddivipol IN $queryDivipol 
	AND pm.codtransmision = mv.codtransmision
	AND pc.idcandidato = mv.idcandidato AND pm.codcorporacion = co.codcorporacion $texto1
	GROUP BY co.descripcion,pp.descripcion ORDER BY co.descripcion,votos DESC;
EOF;
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);

?>