<?php
	
	$coddivipol = $datos['coddivipol'];
	$codnivel   = $datos['codnivel'];
	$codcordivi = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query =<<<EOF
	SELECT pc.codpartido || '-' || pc.codcandidato as codigo  ,pc.nombres as nombres ,pc.apellidos as apellidos ,sum(mv.numvotos) as votos
	FROM pmesas pm, mvotos mv, pcandidatos pc
	WHERE pm.coddivipol LIKE '$codcordivi' || '%'
	AND pc.coddivipol LIKE '$codcordivi' || '%' AND pc.codnivel = $codnivel
	AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
	AND pc.idcandidato = mv.idcandidato
	GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
	ORDER BY votos DESC;
EOF;

	$result   = ibase_query($firebird,$query);
?>