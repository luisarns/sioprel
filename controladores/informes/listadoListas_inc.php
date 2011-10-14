<?php
	
	$codcorpo    = $datos->codcorporacion;
	$nivcorpo    = $datos->corpnivel;
	$codcordiv   = substr($datos->coddivipol,0,getNumDigitos($nivcorpo));
	$coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$txt = "";
	if(isset($datos->idcomuna)) {
		$txt = "AND pc.idcomuna = $datos->idcomuna ";
	}
	
	$query =<<<EOF
	SELECT pc.nombres as descripcion, SUM(mv.numvotos) as votos
		FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
		WHERE pm.codtransmision = mv.codtransmision 
		AND pc.idcandidato = mv.idcandidato 
		AND pc.coddivipol LIKE '$codcordiv' || '%'
		AND pm.coddivipol LIKE '$coddivcorto' || '%'
		AND pm.codcorporacion = $codcorpo 
		AND pc.codnivel = $nivcorpo $txt
        AND pc.codcandidato = 0
		GROUP BY pc.nombres
        ORDER BY votos DESC
EOF;
	
	$result   = ibase_query($firebird,$query);
?>