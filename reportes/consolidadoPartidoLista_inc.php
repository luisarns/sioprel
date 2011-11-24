<?php
	
    $coddivipol = $_GET['coddivipol'];
    $codnivel = $_GET['codnivel'];
    $codcorporacion = $_GET['codcorporacion'];
    $nivcorpo = $_GET['nivcorpo'];
    $codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));

    $texto1 = " ";
    $hayMesa = false;
    if (isset($_GET['codtransmision'])) {
        $texto1 = " AND pm.codtransmision = '".$_GET['codtransmision']."'";
        $hayMesa = true;
    }

    $texto2 ="";
    $hayComuna = false;
    if (isset($_GET['idcomuna'])) {
        $texto2 = " AND pc.idcomuna = ".$_GET['idcomuna'];
        $hayComuna = true;
    }

    $texto3 = "";
    $txt4 = "";
    if (isset($_GET['codpartido'])) {
        $texto3 = " AND pp.codpartido = ".$_GET['codpartido'];
        $txt4 = "AND pc.codpartido = ".$_GET['codpartido'];
    }

    $query =<<<EOF
        SELECT pp.codpartido as codigo ,pp.descripcion as descripcion, SUM(mv.numvotos) as votos
        FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv, pdivipol pd
        WHERE pp.codpartido = pc.codpartido $texto1
        AND pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4
        AND pm.coddivipol = pd.coddivipol
        AND pm.codtransmision = mv.codtransmision $texto2
        AND pc.idcandidato = mv.idcandidato $texto3
        AND pc.coddivipol LIKE '$codcordiv'  || '%'
        AND pc.codnivel = $nivcorpo
        AND pm.codcorporacion = $codcorporacion
        GROUP BY pp.codpartido, pp.descripcion
EOF;

    ///Inicio query potencial
    $queryPotencial = <<<FEO
    SELECT potencialf,potencialm 
    FROM pdivipol
    WHERE coddivipol LIKE '$coddivipol' || '%'
    AND codnivel = $codnivel 
FEO;
    if ($hayMesa) {
        $codtransmision = $_GET['codtransmision'];
        $queryPotencial = "
        SELECT numvotos as potencialf, 0 potencialm
        FROM ptiposmesas pt,pmesas pm
        WHERE pm.codtransmision = '$codtransmision'
        AND pm.codtipo = pt.codtipo";
    } else if ($hayComuna && $codnivel != 4) {
        $idcomuna = $_GET['idcomuna'];
        $queryPotencial = "
        SELECT sum(potencialf) as potencialf,sum(potencialm) as potencialm
        FROM pdivipol
        WHERE coddivipol LIKE '$coddivipol' || '%'
        AND codnivel = 4 
        AND idcomuna = $idcomuna
        GROUP BY codnivel";
    }
    ///Fin query potencial

    //Inicio query votacion especial
    $circunscripcion = ($codcorporacion != 5)? $nivcorpo : 3;
    $txt1 = ($hayComuna)? " AND pd.idcomuna = " . $_GET['idcomuna'] : "";
    $txt1 = ($codnivel == 4)? "" : "";
    $txt1 = ($hayMesa)? " AND pm.codtransmision = '" . $_GET['codtransmision'] . "'" : "";
    
    $queryVotosEsp =<<<OEF
    SELECT pc.codtipovoto,pc.descripcion, SUM(mv.numvotos) as votos
    FROM PMESAS pm, PTIPOSVOTOS pc, MVOTOSESPECIALES mv, pdivipol pd
    WHERE pd.coddivipol LIKE '$coddivipol' || '%' 
    AND pd.codnivel = 4 
    $txt1
    AND pm.coddivipol = pd.coddivipol
    AND pm.codcorporacion = $codcorporacion
    AND pm.codtransmision = mv.codtransmision
    AND pc.codtipovoto = mv.codtipovoto
    AND mv.codcircunscripcion = $circunscripcion
    GROUP BY pc.codtipovoto,pc.descripcion
    ORDER BY votos DESC
OEF;
    
    //Desde aqui cambia el codigo para la coneccion
    $sqlite = new SPSQLite($pathDB);
    
    $sqlite->query($query);
    $result = $sqlite->returnRows();

    $result1 = null;
    $query1 = null;

    if(isset($_GET['detallado']) && $_GET['detallado'] == 1) {
        $query1 =<<<EOR
        SELECT pc.codpartido,pc.codcandidato, pc.nombres ||' '|| CASE WHEN pc.codcandidato = 0 THEN '(LISTA)' ELSE pc.apellidos END as descripcion, SUM(mv.numvotos) as votos
        FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
        WHERE pm.codtransmision = mv.codtransmision $texto1
        AND pc.idcandidato = mv.idcandidato $texto2
        AND pc.coddivipol LIKE '$codcordiv' || '%'
        AND pm.coddivipol LIKE '$coddivipol'  || '%'
        AND pm.codcorporacion = $codcorporacion $txt4
        AND pc.codnivel = $nivcorpo
        GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
        $sqlite->query($query1);
        $result1 = $sqlite->returnRows();
    }
    
    //Ejecuto la query en la base, para obtener el potencial
    $sqlite->query($queryPotencial);
    $resultPotencial = $sqlite->returnRows();
    $potencial = $resultPotencial[0]['potencialf'] + $resultPotencial[0]['potencialm'];
    
    //Ejecuto la query en la base, para obtener lo votacion especial
    $sqlite->query($queryVotosEsp);
    $resultVotosEsp  = $sqlite->returnRows();
    
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
    
    if (isset($result1)) {
        foreach($result1 as $row) {
            array_push($candidatos,$row);
        }
    }
    
    //Obtener la corporacion y el potencial
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = utf8_encode($resulCorporacion[0]['descripcion']);
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
    
    $sqlite->query($queryDivipoles);
    $resultDivipol = $sqlite->returnRows();
    
    if (isset($resultDivipol)) {
        foreach ($resultDivipol as $row) {
            $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
            switch($row['codnivel']) {
                case 1:
                    $nmDepartamento = utf8_encode($row['descripcion']);
                    break;
                case 2:
                    $nmMunicipio = utf8_encode($row['descripcion']);
                    break;
                case 3:
                    $nmZona = utf8_encode($row['descripcion']);
                    break;
                case 4:
                    $nmPueto = utf8_encode($row['descripcion']);
                    break;
            }
        }
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['idcomuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = utf8_encode($resultDivipol[0]['descripcion']);
        $nmZona = ""; 
    }
    if ($hayMesa) {
        $queryDivipol = "SELECT codmesa FROM pmesas WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND codtransmision = " . $_GET['codtransmision'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmMesa = 'Mesa ' . str_pad($resultDivipol[0]['codmesa'],3,'0',STR_PAD_LEFT);
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
    $participacion = round((($totalVotos*100)/$potencial),2);
    $asbtencion  = round(100 - $participacion,2);
    
?>