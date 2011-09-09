<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$coddivipol      = $_GET['coddivipol'];
	$codnivel       = $_GET['codnivel'];
	$codcorporacion = $_GET['codcorporacion'];
	
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	
	$query =<<<EOF
	SELECT codtransmision as codTx , codmesa as mesa FROM pmesas
	WHERE coddivipol = $coddivipol AND codnivel = $codnivel
	AND codcorporacion = $codcorporacion
EOF;
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	
	$mesas = array("total"=>$numRows,"datos"=>array());
	
	if($numRows > 1){
		foreach($rows as $row){
			array_push($mesas['datos'],$row);
		}
	} else if ($numRows == 1) {
		array_push($mesas['datos'],$rows);
	}
	
	$sqlite->close();
	echo json_encode($mesas);
	unset($sqlite);
?>