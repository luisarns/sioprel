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
	SELECT pc.codpartido as codpartido, pc.codcandidato as codcandidato, pc.nombres as nombres, 
	pc.apellidos as apellidos, pp.descripcion as descripcion,sum(mv.numvotos) as votos
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
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();

    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = utf8_encode($resulCorporacion[0]['descripcion']);
    //Fin de la consulta

    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($codcordivi,2);
    
    $nmDepartamento = "";
    $nmMunicipio = "";
    $nmZona = "";
    $nmPueto = "";
    $nmComuna = "";
    
    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    
    if (isset($resultDivipol)) {
        foreach ($resultDivipol as $row) {
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
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($codcordivi, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = utf8_encode($resultDivipol[0]['descripcion']);
        $nmZona = ""; 
    }

    $sqlite->close(); 
    unset($sqlite)
    
?>