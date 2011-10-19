<?php
	
	$opcion   = $_GET['opcion'];
	$divipol  = $_GET['divipol'];
	
	if(isset($opcion) && is_numeric($opcion)){
		
		require('conexion.php');
		
		$cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
		$query = "SELECT idcomuna,codcomuna,descripcion FROM pcomuna WHERE coddivipol LIKE $divipol || '%' ORDER BY codcomuna,descripcion";
		
		$result = ibase_query($cnh,$query);
		
		
		echo "Comuna : <select id='selcomuna' name='comuna' onChange='comunaCargaPuesto(this.value)' >";
		echo "<option value = '-' >-</option>";
		
		while($row = ibase_fetch_object($result)) {
			echo "<option value = '$row->IDCOMUNA' >$row->CODCOMUNA-$row->DESCRIPCION</option>";
		}
		
		echo "</select>";
	}
	
?>