<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$codpartido = $_POST['codpartido'];
	$estado = "INSCRIPTO";
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query ='SELECT codcandidato,nombres,apellidos, "'.$estado.'" estado FROM PCANDIDATOS WHERE codpartido = '.$codpartido.' ORDER BY codcandidato';
	
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