<?php
	
	$coddivipol     = $datos['coddivipol'];
	$codnivel       = $datos['codnivel'];
	$codcorporacion = $datos['codcorporacion'];
	$nivcorpo       = $datos['nivcorpo'];
	
	$codcordivi = substr($coddivipol,0,getNumDigitos($codnivel));
	$cordivi    = substr($coddivipol,0,getNumDigitos($nivcorpo));
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query =<<<EOF
	SELECT pc.codpartido || '-' || pc.codcandidato as codigo  ,pc.nombres as nombres ,pc.apellidos as apellidos ,sum(mv.numvotos) as votos
	FROM pmesas pm, mvotos mv, pcandidatos pc
	WHERE pm.coddivipol LIKE '$codcordivi' || '%'
	AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
	AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
	AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
	GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
	ORDER BY votos DESC;
EOF;

	$result   = ibase_query($firebird,$query);
?>