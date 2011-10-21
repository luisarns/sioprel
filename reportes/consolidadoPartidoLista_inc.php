<?php
	
	$coddivipol = $_GET['coddivipol'];
	$codnivel = $_GET['codnivel'];
	$codcorporacion = $_GET['codcorporacion'];
	$nivcorpo = $_GET['nivcorpo'];
	$codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));
	
	$texto1 = " ";
	if(isset($_GET['codtransmision'])){
		$texto1 = " AND pm.codtransmision = '".$_GET['codtransmision']."'";
	}
	
	$texto2 ="";
	if(isset($_GET['idcomuna'])){
		$texto2 = " AND pc.idcomuna = ".$_GET['idcomuna'];
	}
	
	$texto3 = "";
	$txt4 = "";
	if(isset($_GET['codpartido'])){
		$texto3 = " AND pp.codpartido = ".$_GET['codpartido'];
		$txt4 = "AND pc.codpartido = ".$_GET['codpartido'];
	}
	
	$query =<<<EOF
		SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
		FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv, pdivipol pd
		WHERE pp.codpartido = pc.codpartido $texto1
		AND pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4
		AND pm.coddivipol = pd.coddivipol
		AND pm.codtransmision = mv.codtransmision $texto2
		AND pc.idcandidato = mv.idcandidato $texto3
		AND pc.coddivipol LIKE '$codcordiv'  || '%'
		AND pc.codnivel = $nivcorpo
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pp.codpartido, pp.descripcion
EOF;
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);
	
	$result1 = null;
	$query1 = null;
	
	if(isset($_GET['detallado']) && $_GET['detallado'] == 1) {
		$query1 =<<<EOR
		SELECT pc.codpartido,pc.codcandidato, pc.nombres ||' '|| CASE WHEN pc.codcandidato = 0 THEN '(LISTA)' ELSE pc.apellidos END as descripcion, SUM(mv.numvotos) as votos
		FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
		WHERE pm.codtransmision = mv.codtransmision $texto1
		AND pc.idcandidato = mv.idcandidato $texto2
		AND pc.coddivipol LIKE '$codcordiv' || '%'
		AND pm.coddivipol LIKE '$coddivipol'  || '%'
		AND pm.codcorporacion = $codcorporacion $txt4
		AND pc.codnivel = $nivcorpo
		GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
		$result1 = ibase_query($firebird,$query1);
	}
?>