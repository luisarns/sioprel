<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg()) ;
	
	$text = "";
	if(isset($_POST['tipoeleccion'])){
		$tpeleccion = $_POST['tipoeleccion'];
		$text = "WHERE tipoeleccion = $tpeleccion";
	}
	
	$query ="SELECT codcorporacion,descripcion,codnivel,tipoeleccion,comuna FROM pcorporaciones $text ORDER BY codcorporacion";
	
	$result = ibase_query($firebird,$query);
	
	$datos = array();
	while($row = ibase_fetch_object($result)){
		array_push($datos,array('codcorpo'=>$row->CODCORPORACION,'descorpo'=>$row->DESCRIPCION,'codnivel'=>$row->CODNIVEL,'tipoeleccion'=>$row->TIPOELECCION,'comuna'=>$row->COMUNA));
	}
	
	ibase_free_result($result);
	ibase_close($firebird);
	echo json_encode($datos);
	
?>