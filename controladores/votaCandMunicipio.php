<?php
	session_start();
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$coddivipol     = $_POST['coddivipol'];
	$codnivel       = $_POST['codnivel'];
	$codcorporacion = $_POST['codcorporacion'];
	$nivcorpo = $_POST['nivcorpo'];
	
	//La votacion obtenida por los candidatos de la corporacion
	$datos['coddivipol'] = $coddivipol;
	$datos['codnivel'] = $codnivel;
	$datos['codcorporacion'] = $codcorporacion;
	$datos['nivcorpo'] = $nivcorpo;
	
	$_SESSION['votaCandMunicipio'] = serialize($datos);
	
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
	ORDER BY pc.codpartido, pc.codcandidato;
EOF;

	$result   = ibase_query($firebird,$query);
	
	$arrCandidatos = array();
	while($row = ibase_fetch_object($result)){
		$candidato = array();
		$candidato['codigo'] = $row->CODIGO;
		$candidato['nombres'] = htmlentities($row->NOMBRES);
		$candidato['apellidos'] = htmlentities($row->APELLIDOS);
		$candidato['votos'] = $row->VOTOS;
		array_push($arrCandidatos,$candidato);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($arrCandidatos);
	
?>