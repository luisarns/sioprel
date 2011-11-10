<?php
    require('conexion.php');
    include_once('FunDivipol.php');
    
    $urlReportes = "http://" . $_SERVER['HTTP_HOST'] . "/reportes/repConParDepto.php" . $_SERVER['REQUEST_URI'];
    $urlReportes .= "&formato=";
    
    echo "Por ahora no hay nada pero aqui va la tabla con los resultados de la " 
        . "consulta <strong>coddivipol : " . $_GET['coddivipol'] . " </strong>";
    sleep(3);
?>