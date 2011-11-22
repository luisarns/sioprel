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
        SELECT pc.codcandidato, pc.nombres, pc.apellidos , pc.elegido
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
    $nomCorporacion = utf8_encode($resulCorporacion[0]['descripcion']);
    
    
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
    
    $queryPartido = <<<PAR
        SELECT descripcion
        FROM ppartidos
        WHERE codpartido = $codpartido
PAR;
    
    $sqlite->query($queryPartido);
    $resulPartido = $sqlite->returnRows();
    $nomPartido = utf8_encode($resulPartido[0]['descripcion']);
    
    //Cierro la coneccion a la base de datos
    $sqlite->close(); 
    unset($sqlite)
    
?>