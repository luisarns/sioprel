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
	SELECT '$corpdesc' divipol ,co.codcorporacion as codigo, co.descripcion as descripcion,'VOTOS CANDIDATOS' tipovotos, sum(mv.numvotos) as votos
	FROM pcorporaciones co, pmesas pm, mvotos mv
	WHERE co.codcorporacion = pm.codcorporacion
	AND pm.coddivipol LIKE $coddivcorto || '%' AND pm.codtransmision = mv.codtransmision
	$texto1
	GROUP BY co.codcorporacion,co.descripcion
	UNION
	SELECT '$corpdesc' divipol,co.codcorporacion as codigo, co.descripcion as descripcion,pt.descripcion as tipovotos ,sum(ms.numvotos) as votos
	FROM pcorporaciones co, pmesas pm, mvotosespeciales ms, ptiposvotos pt
	WHERE co.codcorporacion = pm.codcorporacion --AND co.codcorporacion = :codcorporacion
	AND pm.coddivipol LIKE $coddivcorto || '%' AND pm.codtransmision = ms.codtransmision
	AND pt.codtipovoto = ms.codtipovoto $texto1
	GROUP BY co.codcorporacion,co.descripcion,pt.descripcion
EOF;
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);

?>