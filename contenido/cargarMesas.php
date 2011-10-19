<?php
	//la carga de los puestos, los cuales pueden ser dado una zona o una comuna
	$divipol  = $_GET['divipol'];
	$corpo    = $_GET['corporacion'];
		
	require('conexion.php');
	
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT codtransmision,codmesa FROM pmesas WHERE coddivipol = '$divipol' AND codnivel = 4 AND codcorporacion = $corpo ORDER BY codmesa";
	$result = ibase_query($cnh,$query);
	
	echo "Mesa : <select id='selmesa' name='mesa'>";
	echo "<option value = '-' >-</option>";
	while($row = ibase_fetch_object($result)) {
		echo "<option value = '$row->CODTRANSMISION' >$row->CODMESA</option>";
	}	
	echo "</select>";
	
?>