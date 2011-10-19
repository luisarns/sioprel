<?php
	//la carga de los puestos, los cuales pueden ser dado una zona o una comuna
	$divipol  = $_GET['divipol'];
	
	$txt = "";
	if(isset($_GET['idcomuna'])){
		$txt = "AND idcomuna = ".$_GET['idcomuna'];
	}
	
	if(isset($_GET['zona'])){
		$divipol .= $_GET['zona'];
	}
	
	require('conexion.php');
	
	$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
	$query = "SELECT coddivipol,codpuesto,descripcion FROM pdivipol WHERE coddivipol LIKE $divipol || '%' AND codnivel = 4 $txt ORDER BY codpuesto,descripcion";
	$result = ibase_query($cnh,$query);
	
	echo "Puesto : <select id='selpuesto' name='puesto' onChange='cargarMesas(this.value)' >";
	echo "<option value = '-' >-</option>";
	while($row = ibase_fetch_object($result)) {
		echo "<option value = '$row->CODDIVIPOL' >$row->CODPUESTO-$row->DESCRIPCION</option>";
	}	
	echo "</select>";
	
?>