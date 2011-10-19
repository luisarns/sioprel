<?php
	
	$opcion = $_GET['opcion'];
	$corpo  = $_GET['corporacion'];
	
	if(isset($opcion) && is_numeric($opcion)){
		
		require('conexion.php');
		
		$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
		$query = "SELECT codmunicipio,descripcion FROM pdivipol WHERE codnivel = 2 AND coddepartamento = '$opcion' ORDER BY descripcion";
		
		$result = ibase_query($cnh,$query);
		
		$fun = ($corpo != 5)?"cargarZonas":"cargarComunas";
		
		echo "Municipio : <select name='municipio' id='selmunicipio' onChange = $fun(this.value)>";
		echo "<option value = '-' >-</option>";
		
		while($row = ibase_fetch_object($result)) {
			echo "<option value = '$row->CODMUNICIPIO' >$row->DESCRIPCION</option>";
		}
		
		echo "</select>";
	}
	
?>