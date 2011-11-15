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
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
            $tx3 = "AND pc.idcomuna = ".$_GET['comuna'];
            $hayComuna = true;
    }

    $nivcorpo = getNivelCorporacion($codcorporacion);
    $cordivi = substr($coddivipol,0,getNumDigitos($nivcorpo));

    $query =<<<EOF
    SELECT lpad(pp.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
    FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv, pdivipol pd
    WHERE pc.coddivipol LIKE '$cordivi'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorporacion
    AND pd.coddivipol   LIKE '$coddivipol' || '%' AND pm.codtransmision = mv.codtransmision
    AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0 $tx1
    AND pd.coddivipol = pm.coddivipol AND pd.codnivel = 4 $tx2
    AND pm.codcorporacion = $codcorporacion $tx3
    AND pc.elegido <> '0'
    GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion
EOF;

    $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $result   = ibase_query($firebird,$query);
    
    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = utf8_encode($row->DESCRIPCION);
    //Fin de la consulta
    
     include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($coddivipol,$codnivel);

    $resultDivipol = ibase_query($firebird, $queryDivipol);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
	
?>