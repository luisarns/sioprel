<?php
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';

	$raizCoddivipol;
	$raizNivel;

	if(isset($_POST['coddivipol']) && isset($_POST['nivel'])){
		$raizCoddivipol = $_POST['coddivipol'];
		$raizNivel = $_POST['nivel'];
	} else { 
		die( "Error, debe definir los par&aacute;metros para el componente"); 
	}

	$sqlite = new SPSQLite(PATH_DB.'elecciones2011.db');
	$codDivCorto = substr($raizCoddivipol,0,getNumDigitos($raizNivel));
	
	$nivel = $raizNivel + 1;
	$query =<<<EOF
	SELECT coddivipol,descripcion,codnivel 
	FROM pdivipol 
	WHERE coddivipol LIKE '$codDivCorto' || '%' AND codnivel = $nivel
	ORDER BY coddivipol
EOF;

	$nodos = array();

	$sqlite->query($query);
	$arrHijos = $sqlite->returnRows('assoc');
	
	if($sqlite->numRows() > 1 ){ //Cuando tiene mas de un nodo hijo
		foreach($arrHijos as $nHijo){
			$cdcrdivipol = substr($nHijo['coddivipol'],0,getNumDigitos($nHijo['codnivel']));
			$nHijo['text'] = $cdcrdivipol.'-'.$nHijo['descripcion'];
			$nHijo['leaf'] = ($nHijo['codnivel'] != 4)? (tieneHijos($cdcrdivipol,intval($nHijo['codnivel']),$sqlite))? false : true : true;
			array_push($nodos,$nHijo);
		}
	} else if($sqlite->numRows() == 1 ){ //Cuando la divipol tiene un solo nodo hijo
		$cdcrdivipol = substr($arrHijos['coddivipol'],0,getNumDigitos($arrHijos['codnivel']));
		$arrHijos['text'] = $cdcrdivipol.'-'.$arrHijos['descripcion'];
		$arrHijos['leaf'] = ($arrHijos['codnivel'] != 4)?(tieneHijos($cdcrdivipol,intval($arrHijos['codnivel']),$sqlite))?false:true:true;
		array_push($nodos,$arrHijos);
	}
	
	$sqlite->close();
	echo json_encode($nodos);
	unset($sqlite);
	
?>