<?php
	$opcion = $_GET['opcion'];
	$corpo  = $_GET['corporacion'];
	
	if (isset($opcion) && is_numeric($opcion) ) {
            require('conexion.php');

            $cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
            $query = "SELECT codmunicipio,descripcion FROM pdivipol WHERE codnivel = 2 AND coddepartamento = '$opcion' ORDER BY descripcion";

            $result = ibase_query($cnh,$query);

            $fun = ($corpo != 5)?"cargarZonas":"cargarComunas";

            echo "Municipio : <select name='municipio' id='selmunicipio' onChange = $fun(this.value)>";
            echo "<option value = '-' >-Ninguna-</option>";
            
            while($row = ibase_fetch_object($result)) {
                echo "<option value = '$row->CODMUNICIPIO' >" . str_pad($row->CODMUNICIPIO, 3, '0', STR_PAD_LEFT) . '-' . utf8_encode($row->DESCRIPCION) . "</option>";
            }
            
            echo "</select>";

            ibase_free_result($result);
            ibase_close($cnh);
	}
?>