<?php
	
	// $codcorpo    = $datos->codcorpo;
	// $nivcorpo    = $datos->nivcorpo;
	// $codcordiv   = substr($datos->coddivipol,0,getNumDigitos($nivcorpo));
	// $coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	
	// $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	// $query =<<<EOF
	// SELECT pp.codpartido ||'-'|| pc.codcandidato as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
    // FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv
    // WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorpo
    // AND pm.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
    // AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0
    // GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion;
// EOF;
	
	// //pc.codcandidato <> 0 para seleccionar solo los candidatos evitar las listas
	
	// $result   = ibase_query($firebird,$query);
?>