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
	
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());

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
		$result = ibase_query($firebird,$query);
		
		while($row = ibase_fetch_object($result)){
			$nHijo = array();
			$cdcrdivipol = substr($row->CODDIVIPOL,0,getNumDigitos($row->CODNIVEL));
			$nHijo['text'] = $cdcrdivipol.'-'.htmlentities($row->DESCRIPCION);
			$nHijo['leaf'] = ($row->CODNIVEL < $nivelmax)? (tieneHijos($cdcrdivipol,intval($row->CODNIVEL),$firebird))? false : true : true;
			$nHijo['coddivipol']  = $row->CODDIVIPOL;
			$nHijo['descripcion'] = $row->DESCRIPCION;
			$nHijo['codnivel']    = $row->CODNIVEL;
			if(isset($row->IDCOMUNA)){$nHijo['idcomuna']    = $row->IDCOMUNA;}
			
			array_push($nodos,$nHijo);
		}
		
		ibase_free_result($result);
		ibase_close($firebird);
		echo json_encode($nodos);
	}
?>