<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$comunas = array();
	
	if(isset($_POST['coddivipol']) && isset($_POST['codnivel'])){
		$coddivipol      = $_POST['coddivipol'];
		$codnivel       = $_POST['codnivel'];
		
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		
		$query =<<<EOF
		SELECT idcomuna,codcomuna,descripcion
		FROM pcomuna
		WHERE coddivipol = '$coddivipol' AND codnivel = $codnivel
EOF;
	
		$result = ibase_query($firebird,$query);
		while($row = ibase_fetch_object($result)){
			$comuna = array('idcomuna'=>$row->IDCOMUNA,'codcomuna'=>$row->CODCOMUNA,'descripcion'=>htmlentities($row->DESCRIPCION));
			array_push($comunas,$comuna);
		}
		
		ibase_free_result($result);
		ibase_close($firebird);
	}
	echo json_encode($comunas);

?>