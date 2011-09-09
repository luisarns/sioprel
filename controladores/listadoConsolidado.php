<?php 
	session_start(); 
	
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$coddivipol     = $_SESSION['coddivipol'];
	$codnivel       = $_SESSION['codnivel'];
	$codcorporacion = $_SESSION['codcorporacion'];
	$corpnivel      = $_SESSION['corpnivel'];
	$coddivcorto    = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$codcordiv      = str_pad(substr($coddivipol,0,getNumDigitos($corpnivel)),9,'0');
	
	$texto1 = "";
	$texto2 = "";
	
	$idpartido = $_SESSION['idpartido'];
	$idlista = $_SESSION['idlista'];
	$idmesa = $_SESSION['idmesa'];
	
	$texto1 .= (isset($_SESSION['idpartido']))?"AND pc.codpartido = $idpartido ":"";
	$texto1 .= (isset($_SESSION['idlista']))?"AND pc.tipolista = $idlista ":"";
	$texto2 .= (isset($_SESSION['idmesa']))?"AND pm.codtransmision = $idmesa ":""; //cuando se asigna el codigo de transmision de la mesa
	
	//espacio para obtener el nombre de la corporacion mediante su codigo
	
	$mesaQuery = "pmesas";
	
	if(isset($_SESSION['idcomuna'])){ 
		/*
		* Esta subconsulta me trae todas las mesas ubicadas en los puestos de 
		* una comuna identificada por $idcomuna
		*/
		$idcomuna = $_SESSION['idcomuna'];
		$mesaQuery =<<<EOF
		(SELECT m.codtransmision as codtransmision, m.coddivipol as coddivipol, m.codcorporacion as codcorporacion
		FROM pdivipol pd, pmesas m
		WHERE pd.coddivipol = m.coddivipol AND 
		pd.coddivipol LIKE '$coddivcorto' || '%' AND pd.codnivel = 4
		AND pd.idcomuna = $idcomuna AND m.codcorporacion = $codcorporacion)
EOF;
	}
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	
	$query =<<<EOF
	SELECT 'ALCALDIA' corporacion,1 lista,ppc.descripcion as partido,sum(pmv.totalVotos) as votos
	FROM (SELECT pp.descripcion,pc.idcandidato 
			FROM ppartidos pp, pcandidatos pc
			WHERE pp.codpartido = pc.codpartido 
			AND pc.coddivipol = '$codcordiv' AND pc.codnivel = $corpnivel
			AND pc.codcorporacion = $codcorporacion $texto1) ppc,
		(SELECT mv.idcandidato,sum(mv.numvotos) as totalVotos
			FROM $mesaQuery pm, mvotos mv
			WHERE pm.coddivipol LIKE '$coddivcorto' || '%' AND pm.codcorporacion = $codcorporacion 
			AND mv.codtransmision = pm.codtransmision $texto2 GROUP BY mv.idcandidato ORDER BY mv.idcandidato) pmv
	WHERE ppc.idcandidato = pmv.idcandidato
	GROUP BY ppc.descripcion
EOF;
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	$resulConsol = array("total"=>$numRows,"datos"=>array());
	
	//para el caso que se trata de una solo fila
	if($numRows > 1 ){
		foreach($rows as $row) {
			array_push($resulConsol['datos'],$row);
		}
	} else if ($numRows == 1){
		array_push($resulConsol['datos'],$rows);
	}
	
	$sqlite->close();
	echo json_encode($resulConsol);
	unset($sqlite);
?>