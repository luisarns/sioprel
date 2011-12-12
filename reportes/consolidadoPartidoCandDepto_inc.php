<?php
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    $codpartido = $_GET['codpartido'];
    
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = "";
    $hayComuna = false;
    if ($_GET['comuna'] !='-') {
        $txt = " AND pc.idcomuna = " . $_GET['comuna'];
        $hayComuna = true;
    }
    
    $nivcorpo = getNivelCorporacion($corporacion);
    $coddivcorpo = str_pad(substr($coddivipol, 0, getNumDigitos($nivcorpo)),9,'0');

    
    $queryInscritos = <<<PAV
        SELECT pc.codcandidato as codcandidato, pc.nombres as nombres, pc.apellidos as apellidos , pc.elegido as elegido
        FROM ppartidos pp, pcandidatos pc
        WHERE pp.codpartido = pc.codpartido
        AND pp.codpartido = $codpartido
        AND pc.coddivipol = '$coddivcorpo'
        AND pc.codcorporacion = $corporacion
        AND pc.codcandidato <> 0
        AND pc.codnivel = $nivcorpo
        $txt
        ORDER BY pc.elegido,pc.codcandidato
PAV;
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($queryInscritos);
    $resultInscritos = $sqlite->returnRows();
    
    
    $queryCorporacion = "SELECT descripcion FROM pcorporaciones"
                      . " WHERE codcorporacion = $corporacion";
    $sqlite->query($queryCorporacion);
    $resulCorporacion  = $sqlite->returnRows();
    $nomCorporacion = $resulCorporacion[0]['DESCRIPCION'];
    
    
    //Codigo para obtener la descripcion completa de la divipol
    include_once('../contenido/FunDivipol.php');
    
    $queryDivipoles = getQueryDivipolCompleta($coddivipol,$codnivel);
    
    $nmDepartamento = "";
    $nmMunicipio = "";
    $nmZona = "";
    $nmPueto = "";
    $nmComuna = "";
    
    $sqlite->query($queryDivipoles);
    $resultDivipol = $sqlite->returnRows();
    
    if (isset($resultDivipol)) {
        foreach ($resultDivipol as $row) {
            $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
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
                  . " AND codnivel = $codnivel AND idcomuna = " . $_GET['comuna'];
        $sqlite->query($queryDivipol);
        $resultDivipol = $sqlite->returnRows();
        $nmComuna = $resultDivipol[0]['DESCRIPCION'];
        $nmZona = ""; 
    }
    
    $queryPartido = <<<PAR
        SELECT descripcion
        FROM ppartidos
        WHERE codpartido = $codpartido
PAR;
    
    $sqlite->query($queryPartido);
    $resulPartido = $sqlite->returnRows();
    $nomPartido = $resulPartido[0]['DESCRIPCION'];
    
    //Cierro la coneccion a la base de datos
    $sqlite->close(); 
    unset($sqlite)
    
?>