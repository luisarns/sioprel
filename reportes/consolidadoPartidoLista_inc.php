<?php
    
    $coddivipol = $_GET['coddivipol'];
    $codnivel = $_GET['codnivel'];
    $codcorporacion = $_GET['codcorporacion'];
    $nivcorpo = $_GET['nivcorpo'];
    $codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));

    $hayMesa = false;
    if (isset($_GET['codtransmision'])) {
        $filtroMesa = " AND codtransmision = '".$_GET['codtransmision']."'";
        $hayMesa = true;
    }

    $hayComuna = false;
    $filtroComuna = "";
    $filtroDivipol = "";
    if (isset($_GET['idcomuna'])) {
        $filtroComuna = " AND idcomuna = ".$_GET['idcomuna'];
        $filtroDivipol = " INNER JOIN PDIVIPOL pd ON me.coddivipol = pd.coddivipol " 
                        . "AND  pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4 AND pd.idcomuna = " . $_GET['idcomuna'];
        $hayComuna = true;
    }

    $filtroPartido = "";
    if (isset($_GET['codpartido'])) {
        $filtroPartido = "AND pp.codpartido = ".$_GET['partido'];
    }
    
   //Editando 
    $query =<<<EOF
    SELECT pp.codpartido as codigo ,pp.descripcion as descripcion, SUM(mv.numvotos) as votos
    FROM ( SELECT me.codtransmision,me.coddivipol
        FROM PMESAS me
        $filtroDivipol
        WHERE me.codcorporacion = $codcorporacion AND me.coddivipol LIKE '$coddivipol' || '%' $filtroMesa ) pm,
        ( SELECT codpartido,idcandidato
        FROM PCANDIDATOS 
        WHERE coddivipol LIKE '$codcordiv' || '%' AND codnivel = $nivcorpo AND codcorporacion = $codcorporacion ) pc
    INNER JOIN MVOTOS mv ON pm.codtransmision = mv.codtransmision AND mv.idcandidato = pc.idcandidato     
    INNER JOIN PPARTIDOS pp ON pp.codpartido = pc.codpartido $filtroPartido
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EOF;
    //Fin edicion
    
//    echo "Consolidado Partido<br/>" . $query;
    
    if ($codnivel <= 2 ){
     $query =<<<EIF
     SELECT pp.codpartido as codigo ,pp.descripcion as descripcion, sum(dd.numvotos) as votos
     FROM  PPARTIDOS  pp,
     ( SELECT codpartido,idcandidato
       FROM PCANDIDATOS
       WHERE codcorporacion = $codcorporacion
       AND coddivipol LIKE '$codcordiv' || '%'
       AND codnivel = $nivcorpo $filtroComuna ) pc,
     ( SELECT * 
       FROM DDETALLEBOLETIN 
       WHERE coddivipol LIKE '$coddivipol' || '%' 
       AND codnivel = $codnivel AND codcorporacion = $codcorporacion $filtroComuna ) dd
    WHERE pp.codpartido = pc.codpartido AND pc.idcandidato = dd.idcandidato $filtroPartido
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EIF;
    }
    
//    echo "Consolidado Partido<br/>" . $query;
    
    ///Inicio query potencial
    $queryPotencial = <<<FEO
    SELECT potencialf ,potencialm 
    FROM pdivipol
    WHERE coddivipol LIKE '$coddivipol' || '%'
    AND codnivel = $codnivel 
FEO;
    
    if ($hayMesa) {
        $codtransmision = $_GET['codtransmision'];
        $queryPotencial = "
        SELECT numvotos as POTENCIALF, 0 POTENCIALM
        FROM ptiposmesas pt,pmesas pm
        WHERE pm.codtransmision = '$codtransmision'
        AND pm.codtipo = pt.codtipo";
        
    } else if ($hayComuna && $codnivel != 4) {
        $idcomuna = $_GET['idcomuna'];
        $queryPotencial = "
        SELECT sum(potencialf) as POTENCIALF,sum(potencialm) as POTENCIALM
        FROM pdivipol
        WHERE coddivipol LIKE '$coddivipol' || '%'
        AND codnivel = 4 
        AND idcomuna = $idcomuna
        GROUP BY codnivel";
        
    }
    ///Fin query potencial

//    echo "<br/>Potencial<br/>" . $queryPotencial;
    
    //Inicio query votacion especial
    $circunscripcion = ($codcorporacion != 5)? $nivcorpo : 3;
    
    $queryVotosEsp = <<<EOF
    SELECT pc.codtipovoto as codtipovoto ,pc.descripcion as descripcion, SUM(mv.numvotos) as votos
    FROM PTIPOSVOTOS pc,
     ( SELECT me.codtransmision,me.coddivipol
       FROM PMESAS me
       WHERE me.codcorporacion = $codcorporacion $filtroMesa ) pm,
     ( SELECT coddivipol, codnivel 
       FROM PDIVIPOL 
       WHERE coddivipol LIKE '$coddivipol' || '%' AND codnivel = 4 $filtroComuna ) pd,
    MVOTOSESPECIALES mv
    WHERE pm.coddivipol = pd.coddivipol AND pm.codtransmision = mv.codtransmision
    AND pc.codtipovoto = mv.codtipovoto AND mv.codcircunscripcion = '$circunscripcion'
    GROUP BY pc.codtipovoto,pc.descripcion 
    ORDER BY votos DESC
EOF;
    
    if ($codnivel <= 2 ) {
     $queryVotosEsp =<<<EOF
     SELECT pc.codtipovoto as codtipovoto ,pc.descripcion as descripcion, SUM(de.numvotos) as votos
     FROM PTIPOSVOTOS pc,
     ( SELECT codtipovoto, numvotos 
       FROM DETALLEBOLETINESP 
       WHERE coddivipol LIKE '$coddivipol' || '%' AND codnivel = $codnivel 
       AND codcircunscripcion = '$circunscripcion'
       AND codcorporacion = $codcorporacion $filtroComuna ) de
    WHERE de.codtipovoto = pc.codtipovoto
    GROUP BY pc.codtipovoto,pc.descripcion 
    ORDER BY votos DESC
EOF;
    }
    
//    echo "<br/>Votos Especiales<br/>" .  $queryVotosEsp;
    
    //Desde aqui cambia el codigo para la coneccion
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();
//    $sqlite->close();
    //
    
    //Ejecuto la query en la base, para obtener el potencial
//    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($queryPotencial);
    $resultPotencial = $sqlite->returnRows();
    $potencial = $resultPotencial[0]['POTENCIALF'] + $resultPotencial[0]['POTENCIALM'];
//    $sqlite->close();
    //
    
//    echo "<br/>" . $potencial . "<br/>";
    
    //Ejecuto la query en la base, para obtener lo votacion especial
//    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($queryVotosEsp);
    $resultVotosEsp  = $sqlite->returnRows();
//    $sqlite->close();
    //
    
    $totalVotos = 0;
    $votacionEspecial = array();
    if (isset($resultVotosEsp)) {
        foreach($resultVotosEsp as $row) {
            array_push($votacionEspecial,$row);
            $totalVotos += $row['votos'];
        }
    }
    
    $partidos = array();
    $candidatos = array();
    
    if (isset($result)) {
        foreach($result as $row) {
            array_push($partidos,$row);
            $totalVotos += $row['votos'];
        }
    }

//    echo "<br/> Total Votos : " . $totalVotos . "<br/>";
    
    //-----------------//--------------------//------------------//
    //Obtener la corporacion y el potencial
//    $sqlite = new SPSQLite($pathDB);
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['DESCRIPCION'];
//    $sqlite->close();
    //Cuando es comuna y cuando es mesa

    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    
    $queryDivipoles = getQueryDivipolCompleta($coddivipol,$codnivel);
    
    $nmDepartamento = "";
    $nmMunicipio = "";
    $nmZona = "";
    $nmPueto = "";
    $nmComuna = "";
    $nmMesa = "";
    
    //
//    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($queryDivipoles);
    $resultDivipol = $sqlite->returnRows();
//    $sqlite->close();
    //
//    echo "<br/>" . $queryDivipoles . "<br/>";
    
    if (isset($resultDivipol)) {
        foreach ($resultDivipol as $row) {
            switch($row['codnivel']) {
                case 1:
                    $nmDepartamento = $row['descripcion'];
                    break;
                case 2:
                    $nmMunicipio = $row['descripcion'];
                    break;
                case 3:
                    $nmZona = $row['descripcion'];
                    break;
                case 4:
                    $nmPueto = $row['descripcion'];
                    break;
            }
        }
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['idcomuna'];
        $sqlite = new SPSQLite($pathDB);
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $sqlite->close(); 
        $nmComuna = $resultDivipol[0]['DESCRIPCION'];
        $nmZona = "";
    }
    
    if ($hayMesa) {
        $queryDivipol = "SELECT codmesa FROM pmesas WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND codtransmision = " . $_GET['codtransmision'];
        $sqlite = new SPSQLite($pathDB);
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $sqlite->close();
        $nmMesa = 'Mesa ' . str_pad($resultDivipol[0]['CODMESA'],3,'0',STR_PAD_LEFT);
    }
    
    $sqlite->close();
    unset($sqlite);
    
    $participacion = round((($totalVotos*100)/$potencial),2);
    $asbtencion  = round(100 - $participacion,2);
?>