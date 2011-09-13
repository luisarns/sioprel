<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$coddivipol = $_POST['coddivipol'];
	$codnivel   = $_POST['codnivel'];
	$codcordivi = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
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
	//Actualizar la consulta para generar un consolidado por partidos
	
	//echo $query;//Just Test
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	
	$arrCandidatos = array();
	if($numRows > 1){	
		foreach($rows as $row){
			$row['descripcion'] = htmlentities($row['descripcion']);
			array_push($arrCandidatos,$row);
		}
	}else if($numRows == 1){
		$rows['descripcion'] = htmlentities($rows['descripcion']);
		array_push($arrCandidatos,$rows);
	}
	
	$sqlite->close();
	echo json_encode($arrCandidatos);
	unset($sqlite);
?>