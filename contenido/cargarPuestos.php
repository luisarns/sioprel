<?php
	//la carga de los puestos, los cuales pueden ser dado una zona o una comuna
	$divipol  = $_GET['divipol'];
	
	$txt = "";
	if (isset($_GET['idcomuna'])) {
		$txt = "AND idcomuna = ".$_GET['idcomuna'];
	}
	
	if (isset($_GET['zona'])) {
		$divipol .= $_GET['zona'];
	}
	
	require('conexion.php');
	
	$coneccion = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT coddivipol,codpuesto,descripcion FROM pdivipol WHERE coddivipol LIKE $divipol || '%' "
		   . "AND codnivel = 4 $txt ORDER BY codpuesto,descripcion";
	$result = ibase_query($coneccion,$query);
	
	echo "Puesto : <select id='selpuesto' name='puesto' onChange='cargarMesas(this.value)' >";
	echo "<option value = '-' >-Ninguna-</option>";
	while ($row = ibase_fetch_object($result)) {
		echo "<option value = '$row->CODDIVIPOL' >" . str_pad($row->CODPUESTO, 2, '0', STR_PAD_LEFT) . '-' . utf8_encode($row->DESCRIPCION) . "</option>";
	}
	echo "</select>";
	
	ibase_free_result($result);
	ibase_close($coneccion);
?>