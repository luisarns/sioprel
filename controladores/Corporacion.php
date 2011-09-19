<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg()) ;
	
	$query ='SELECT codcorporacion,descripcion,codnivel,tipoeleccion FROM pcorporaciones ORDER BY codcorporacion';
	$result = ibase_query($firebird,$query);
	
	$datos = array();
	while($row = ibase_fetch_object($result)){
		array_push($datos,array('codcorpo'=>$row->CODCORPORACION,'descorpo'=>$row->DESCRIPCION,'codnivel'=>$row->CODNIVEL,'tipoeleccion'=>$row->TIPOELECCION));
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($datos);
	
?>