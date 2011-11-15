<?php

    $codcorporacion = $_GET['corporacion'];
    $coddepto = $_GET['departamento'];
    $codmunip = $_GET['municipio'];

    $txt = "";
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
            $txt = "AND pc.idcomuna = ".$_GET['comuna'];
            $txt .= " AND pd.idcomuna = ".$_GET['comuna'];
            $hayComuna = true;
    }

    $codcordivi = $coddepto."".$codmunip;
    $nivcorpo = getNivelCorporacion($codcorporacion);
    $cordivi = substr($codcordivi,0,getNumDigitos($nivcorpo));

    $query =<<<EOF
    SELECT lpad(pc.codpartido,3,'0') || '-' || lpad(pc.codcandidato,3,'0') as codigo  ,pc.nombres, 
    pc.apellidos ,pp.descripcion,sum(mv.numvotos) as votos
    FROM pmesas pm, mvotos mv, ppartidos pp, pcandidatos pc, pdivipol pd
    WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
    AND pp.codpartido = pc.codpartido
    AND pd.coddivipol LIKE '$codcordivi' || '%' $txt
    AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
    AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
    AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
    AND pm.codcorporacion = $codcorporacion
    GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos,pp.descripcion
    ORDER BY pc.codpartido, pc.codcandidato
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
    $queryDivipol = getQueryDivipolCompleta($codcordivi,2);

    $resultDivipol = ibase_query($firebird, $queryDivipol);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    if ($hayComuna) {
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($codcordivi, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        
        $resultDivipol = ibase_query($firebird, $queryDivipol);
        $row = ibase_fetch_object($resultDivipol);
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }

    ibase_free_result($resultDivipol);
    ibase_free_result($resulCorporacion);
?>