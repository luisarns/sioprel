<?php
	//la carga de los puestos, los cuales pueden ser dado una zona o una comuna
	$divipol  = $_GET['divipol'];
	
	require('conexion.php');
	
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT codzona,descripcion FROM pdivipol WHERE coddivipol LIKE $divipol || '%' AND codnivel = 3 ORDER BY codzona,descripcion";
	$result = ibase_query($cnh,$query);
	
	echo "Zona : <select id='selzona' name='zona' onChange='zonaCargaPuesto(this.value)' >";
	echo "<option value = '-' >-</option>";
	while($row = ibase_fetch_object($result)) {
		echo "<option value = '$row->CODZONA' >$row->CODZONA-$row->DESCRIPCION</option>";
	}	
	echo "</select>";
	
?>