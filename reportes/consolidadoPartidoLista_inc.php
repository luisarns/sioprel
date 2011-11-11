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
        SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
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
    //Fin query votacion especial

    $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $result   = ibase_query($firebird,$query);

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
        $result1 = ibase_query($firebird,$query1);
    }
    
    //Ejecuto la query en la base, para obtener el potencial
    $resultPotencial  = ibase_query($firebird,$queryPotencial);
    $row = ibase_fetch_object($resultPotencial);
    $potencial = $row->POTENCIALF + $row->POTENCIALM;
    
    
    //Ejecuto la query en la base, para obtener lo votacion especial
    $resultVotosEsp  = ibase_query($firebird,$queryVotosEsp);
    
    $totalVotos = 0;
    $votacionEspecial = array();
    while($row = ibase_fetch_object($resultVotosEsp)) {
        array_push($votacionEspecial,$row);
        $totalVotos += $row->VOTOS;
    }
    
    //Configuracion para la generacion del pdf
    $partidos = array();
    $candidatos = array();
    
    while ($row = ibase_fetch_object($result)) {
            array_push($partidos,$row);
            $totalVotos += $row->VOTOS;
    }
    if ($result1 != null) {
        while($row = ibase_fetch_object($result1)) {
                array_push($candidatos,$row);
        }
    }
    
    //Obtener la corporacion y el potencial
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $codcorporacion";
    $resulCorporacion = ibase_query($firebird, $queryCorporacion);
    $row = ibase_fetch_object($resulCorporacion);
    $nomCorporacion = $row->DESCRIPCION;
    //Cuando es comuna y cuando es mesa
    
    

    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    $codniveltmp = 1;
    $inArrDivipol = array();
    $inArrNivel = array();
    while ($codniveltmp <= $codnivel) {
        array_push($inArrDivipol,str_pad(substr($coddivipol, 0, getNumDigitos($codniveltmp)), 9, '0'));
        array_push($inArrNivel,$codniveltmp);
        $codniveltmp = $codniveltmp + 1;
    }
    $inDivipol = '(' . implode(',',$inArrDivipol) . ')';
    $inNivel = '(' . implode(',',$inArrNivel) . ')';
    
    $queryDivipoles = "SELECT descripcion "
                    . "FROM pdivipol "
                    . "WHERE coddivipol in $inDivipol "
                    . "AND codnivel in $inNivel "
                    . "ORDER BY codnivel";
    
    $resultDivipol = ibase_query($firebird, $queryDivipoles);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    //echo $nomDivipol;
    //implode para obtener un string partiendo de un array
    //Fin del codigo
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['idcomuna'];
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    if ($hayMesa) {
        $queryDivipol = "SELECT codmesa FROM pmesas WHERE coddivipol = '" . str_pad($coddivipol, 9,'0') . "'" 
                  . " AND codnivel = $codnivel AND codtransmision = " . $_GET['codtransmision'];
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' Mesa ' . str_pad($row->CODMESA,3,'0',STR_PAD_LEFT);
    }
    
    
    
    //Libero los recursos de la base de datos
    ibase_free_result($result);
    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
    ibase_free_result($resultVotosEsp);
    ibase_free_result($resultPotencial);
    if($result1 != null){ibase_free_result($result1);}
    ibase_close($firebird);
    
    $participacion = round((($totalVotos*100)/$potencial),2);
    $asbtencion  = round(100 - $participacion,2);
    
?>