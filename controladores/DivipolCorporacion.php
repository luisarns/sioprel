<?php
	header("Content-Type: text/plain");
	require_once 'Configuracion.php';
	require_once 'FunDivipol.php';
	
	if($_POST['nivel'] < $_POST['nivelmax']){
	
	$raizCoddivipol;
	$raizNivel;
	$codCorpo;
	$nivelmax;
	if(isset($_POST['coddivipol']) && isset($_POST['nivel'])){
		$raizCoddivipol = $_POST['coddivipol'];
		$raizNivel      = $_POST['nivel'];
		$codCorpo       = $_POST['codcorpo'];
		$nivelmax       = $_POST['nivelmax'];
	} else {
		die( "Error, debe definir los par&aacute;metros para el componente"); 
	}
	
	$sqlite = new SPSQLite(PATH_DB.'elecciones2011.db');

	$codDivCorto = substr($raizCoddivipol,0,getNumDigitos($raizNivel));
	$nivel = $raizNivel + 1;
	
	$idComuna = (isset($_POST['idcomuna']))?$_POST['idcomuna'] : -1;
	$texto = "";
	if($idComuna != -1 ){
		$nivel = $raizNivel + 2;
		$texto .= " AND idcomuna = $idComuna ";
	}
	
	//Filtro para cargar los municipios zonificados
	$filJal = "";
	if($codCorpo == 5 && $raizNivel == 1){//JAL cuando se trata de un departamento
		$filJal = " AND zonificado = 1";
	}
	
	$query =<<<EOF
	SELECT coddivipol,descripcion,codnivel 
	FROM pdivipol 
	WHERE coddivipol LIKE '$codDivCorto' || '%' AND codnivel = $nivel $filJal $texto
	ORDER BY coddivipol
EOF;
	
	if($raizNivel == 2 && $codCorpo == 5 && $idComuna == -1){ //2 MUNICIPIO Y 5 JAL
		$nivel = $raizNivel;
		$query =<<<EOF
	SELECT pd.coddivipol as coddivipol ,pc.descripcion as descripcion ,pd.codnivel as codnivel ,pc.idcomuna as idcomuna
	FROM pdivipol pd, pcomuna pc
	WHERE pd.coddivipol = pc.coddivipol AND pd.codnivel = pc.codnivel AND
	pd.coddivipol = $raizCoddivipol AND pd.codnivel = $nivel
	ORDER BY pc.idcomuna
EOF;
	}
		
	$nodos = array();

	$sqlite->query($query);
	$arrHijos = $sqlite->returnRows('assoc');
	
	if($sqlite->numRows() > 1 ){
		foreach($arrHijos as $nHijo){
			$cdcrdivipol = substr($nHijo['coddivipol'],0,getNumDigitos($nHijo['codnivel']));
			$nHijo['text'] = $cdcrdivipol.'-'.htmlentities($nHijo['descripcion']);
			$nHijo['leaf'] = ($nHijo['codnivel'] < $nivelmax)? (tieneHijos($cdcrdivipol,intval($nHijo['codnivel']),$sqlite))? false : true : true;
			array_push($nodos,$nHijo);
		}
	} else if($sqlite->numRows() == 1 ){
		$cdcrdivipol = substr($arrHijos['coddivipol'],0,getNumDigitos($arrHijos['codnivel']));
		$arrHijos['text'] = $cdcrdivipol.'-'.htmlentities($arrHijos['descripcion']);
		$arrHijos['leaf'] = ($arrHijos['codnivel'] < $nivelmax)?(tieneHijos($cdcrdivipol,intval($arrHijos['codnivel']),$sqlite))? false : true : true;
		array_push($nodos,$arrHijos);
	}
	
	$sqlite->close();
	echo json_encode($nodos);
	unset($sqlite);
	}
?>