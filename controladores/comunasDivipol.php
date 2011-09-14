<?php 
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	
	$comunas = array();
	
	if(isset($_POST['coddivipol']) && isset($_POST['codnivel'])){
		$coddivipol      = $_POST['coddivipol'];
		$codnivel       = $_POST['codnivel'];
		
		$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
		
		$query =<<<EOF
		SELECT idcomuna,descripcion
		FROM pcomuna
		WHERE coddivipol = '$coddivipol' AND codnivel = $codnivel
EOF;
	
		$sqlite->query($query);
		$rows = $sqlite->returnRows('assoc');
		$numRows = $sqlite->numRows();
		
		if($numRows > 1){
			foreach($rows as $row){
				array_push($comunas,$row);
			}
		} else if ($numRows == 1) {
			array_push($comunas,$rows);
		}
		
		$sqlite->close();
	}
	echo json_encode($comunas);
	unset($sqlite);
?>