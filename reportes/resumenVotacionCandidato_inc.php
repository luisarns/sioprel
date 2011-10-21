<?php

	$codcorporacion = $_GET['corporacion'];
	$coddepto = $_GET['departamento'];
	$codmunip = $_GET['municipio'];
	
	$txt = "";
	if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
		$txt = "AND pc.idcomuna = ".$_GET['comuna'];
		$txt .= " AND pd.idcomuna = ".$_GET['comuna'];
	}
	
	$codcordivi = $coddepto."".$codmunip;
	$nivcorpo = getNivelCorporacion($codcorporacion);
	$cordivi = substr($codcordivi,0,getNumDigitos($nivcorpo));
	
	$query =<<<EOF
		SELECT pc.codpartido || '-' || pc.codcandidato as codigo ,pc.nombres, pc.apellidos ,sum(mv.numvotos) as votos
		FROM pmesas pm, mvotos mv, pcandidatos pc, pdivipol pd
		WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
		AND pd.coddivipol LIKE '$codcordivi' || '%' $txt
		AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
		AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
		AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
		ORDER BY pc.codpartido, pc.codcandidato
EOF;

	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);

?>