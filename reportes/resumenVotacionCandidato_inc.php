<?php

    $codcorporacion = $_GET['corporacion'];
    $coddepto = $_GET['departamento'];
    $codmunip = $_GET['municipio'];
	$codnivel = 2;

    $txt = "";
    $hayComuna = false;
    if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
            $txt = "AND pc.idcomuna = ".$_GET['comuna'];
            $txt .= " AND pd.idcomuna = ".$_GET['comuna'];
            $hayComuna = true;
    }

    $codcordivi = $coddepto."".$codmunip;
    $nivcorpo = getNivelCorporacion($codcorporacion);
    $codcorpodivipol = substr($codcordivi,0,getNumDigitos($nivcorpo));

	$query =<<<EOF
	SELECT pc.codpartido as codpartido, pc.codcandidato as codcandidato, pc.nombres as nombres, pc.apellidos as apellidos,
	pp.descripcion as descripcion,sum(dd.numvotos) as votos
	FROM PPARTIDOS pp, 
     ( SELECT codpartido,codcandidato,idcandidato,nombres,apellidos
       FROM PCANDIDATOS 
       WHERE codcorporacion = $codcorporacion
       AND coddivipol LIKE '$codcorpodivipol' || '%'
       AND codnivel = '$nivcorpo' AND codcandidato <> 0 ) pc,
     ( SELECT * 
       FROM DDETALLEBOLETIN 
       WHERE coddivipol LIKE $codcordivi || '%' 
       AND codnivel = $codnivel AND codcorporacion = $codcorporacion ) dd
	WHERE pc.codpartido = pp.codpartido AND pc.idcandidato = dd.idcandidato
	GROUP BY pc.codpartido, pc.codcandidato
	ORDER BY votos DESC
EOF;
	
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();

    //Para obtener el nombre de la corporacion
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                  . " WHERE codcorporacion = $codcorporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['DESCRIPCION'];
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
        $queryDivipol = "SELECT descripcion FROM pcomuna WHERE coddivipol = '" . str_pad($codcordivi, 9,'0') . "'" 
                  . " AND codnivel = 2 AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = $resultDivipol[0]['DESCRIPCION'];
        $nmZona = ""; 
    }

    $sqlite->close(); 
    unset($sqlite)
    
?>