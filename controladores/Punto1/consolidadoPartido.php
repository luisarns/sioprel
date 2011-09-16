<?php
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$datos = json_decode(str_replace("\\","",$_POST['datos']));
	require_once 'consolidadoPartido_inc.php';
	
	$salida = array();
	if($numRows > 1){
		foreach($rows as $row){
			$row['partido'] = htmlentities($row['partido']);
			array_push($salida,$row);
		}
	}else if ($numRows == 1){
		$rows['partido'] = htmlentities($rows['partido']);
		array_push($salida,$rows);
	}
	echo json_encode($salida);
?>
