<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$coddivipol = $_POST['coddivipol'];
	$codnivel   = $_POST['codnivel'];
	$codcordivi = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$datos['coddivipol'] = $coddivipol;
	$datos['codnivel'] = $codnivel;
	$_SESSION['votaPartMunicipio'] = serialize($datos);
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query =<<<EOF
	SELECT c2.codpartido as codpartido, c2.descripcion as descripcion, sum(c1.votos) as votos
	FROM 
	   (SELECT mv.idcandidato,sum(mv.numvotos) as votos
		FROM pmesas pm, mvotos mv
		WHERE pm.codtransmision = mv.codtransmision
		AND pm.coddivipol LIKE '$codcordivi' || '%'
		GROUP BY mv.idcandidato) c1,
	   (SELECT pp.codpartido,pp.descripcion,pc.idcandidato
		FROM ppartidos pp, pcandidatos pc
		WHERE pc.codpartido = pp.codpartido) c2
	WHERE c1.idcandidato = c2.idcandidato
	GROUP BY c2.codpartido,c2.descripcion ORDER BY c2.codpartido
EOF;
	
	$result   = ibase_query($firebird,$query);
	$arrPartidos = array();
	while($row = ibase_fetch_object($result)){
		$partido = array();
		$partido['codpartido']  = $row->CODPARTIDO;
		$partido['descripcion'] = htmlentities($row->DESCRIPCION);
		$partido['votos'] = $row->VOTOS;
		array_push($arrPartidos,$partido);
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($arrPartidos);
	
?>