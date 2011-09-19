<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$coddivipol = $_POST['coddivipol'];
	$codnivel   = $_POST['codnivel'];
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	$query =<<<EOF
	SELECT codtransmision as codTx , codmesa as mesa FROM pmesas
	WHERE coddivipol = '$coddivipol' AND codnivel = $codnivel
EOF;
	
	$result = ibase_query($firebird,$query);
	
	$mesas = array("total"=>0,"datos"=>array());
	while($row = ibase_fetch_object($result)) {
		array_push($mesas['datos'],array('codTx'=>$row->CODTX,'mesa'=>$row->MESA));
		$mesas['total'] = $mesas['total'] + 1;
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($mesas);
	
?>