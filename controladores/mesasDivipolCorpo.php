<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$coddivipol     = $_POST['coddivipol'];
	$codnivel       = $_POST['codnivel'];
	$codcorporacion = $_POST['codcorporacion'];
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg()) ;
	
	$query =<<<EOF
	SELECT codtransmision,codmesa FROM pmesas
	WHERE coddivipol = '$coddivipol' AND codnivel = $codnivel
	AND codcorporacion = $codcorporacion
EOF;
	
	$result = ibase_query($firebird,$query);
	$numRows = 0;
	$mesas = array("total"=>$numRows,"datos"=>array());
	
	while($row = ibase_fetch_object($result)) {
		$mesa = array('codTx'=>$row->CODTRANSMISION,'mesa'=>$row->CODMESA);
		array_push($mesas["datos"],$mesa);
		$mesas["total"]++;
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($mesas);
?>