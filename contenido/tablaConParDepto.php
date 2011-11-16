<?php
    require('conexion.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParDepto.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";
    
    $corporacion  = $_GET['corporacion'];
    $departamento = $_GET['departamento'];
    
    //El codigo corto de la divipol y su nivel
    $coddivipol = $departamento;
    $codnivel = 1; 
    
    if ($_GET['municipio'] !='-') {
        $coddivipol .= $_GET['municipio'];
        $codnivel += 1; 
    }
    
    $txt = ""; //Filtro para la comuna
    if ($_GET['comuna'] !='-') {
        
    }
    
    echo "divipol : " . str_pad($coddivipol,9,'0') . " codnivel : $codnivel";
    sleep(3);
?>