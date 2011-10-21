<?php

	$codcorporacion = $_GET['corporacion'];
	$coddivipol = $_GET['departamento'];
	$codnivel   = 1;

	if(isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
		$coddivipol .= $_GET['municipio'];
		$codnivel = 2;
	}
	
	$tx1 = "";
	if($_GET['sexo'] != "-") {
		$tx1 = "AND pc.genero='".$_GET['sexo']."'";
	}

	$tx2 = "";
	if($_GET['partido'] != "-") {
		$tx2 = "AND pc.codpartido=".$_GET['partido'];
	}

	$tx3 = "";
	if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
		$tx3 = "AND pc.idcomuna = ".$_GET['comuna'];
	}

	$nivcorpo = getNivelCorporacion($codcorporacion);
	$cordivi = substr($coddivipol,0,getNumDigitos($nivcorpo));

	$query =<<<EOF
	SELECT pc.nombres,pc.apellidos, pp.descripcion, pe.numvotos
	FROM PCANDIDATOS pc, PELEGIDOS pe, PPARTIDOS pp
	WHERE pc.idcandidato = pe.idcandidato $tx1
	AND pc.coddivipol LIKE '$cordivi' || '%' $tx2
	AND pc.codnivel = $nivcorpo $tx3
	AND pp.codpartido = pc.codpartido
	AND pc.codcorporacion = $codcorporacion
EOF;

	$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	$result   = ibase_query($firebird,$query);
	
?>