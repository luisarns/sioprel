<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	$coddivipol = $_POST['coddivipol'];
	$codnivel   = $_POST['codnivel'];
	$codcordivi = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query =<<<EOF
	SELECT mv.idcandidato as idcandidato ,pc.nombres as nombres ,pc.apellidos as apellidos ,sum(mv.numvotos) as votos
	FROM pmesas pm, mvotos mv, pcandidatos pc
	WHERE pm.coddivipol LIKE '$codcordivi' || '%'
	AND mv.codtransmision = pm.codtransmision
	AND pc.idcandidato = mv.idcandidato
	GROUP BY mv.idcandidato,pc.nombres,pc.apellidos ORDER BY mv.idcandidato
EOF;
	
	//echo $query;//Just Test
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	
	$arrCandidatos = array();
	if($numRows > 1){	
		foreach($rows as $row){
			$row['nombres'] = htmlentities($row['nombres']);
			$row['apellidos'] = htmlentities($row['apellidos']);
			array_push($arrCandidatos,$row);
		}
	}else if($numRows == 1){
		$rows['nombres'] = htmlentities($rows['nombres']);
		$rows['apellidos'] = htmlentities($rows['apellidos']);
		array_push($arrCandidatos,$rows);
	}
	
	$sqlite->close();
	echo json_encode($arrCandidatos);
	unset($sqlite);
?>