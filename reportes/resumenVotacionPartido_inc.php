<?php 
    $coddepto = $_GET['departamento'];
    $codnivel = 1;
    $codmunip = "";
    if ($_GET['municipio'] != "-"){
        $codmunip = $_GET['municipio'];
        $codnivel += 1;
    }
    $codcordivi = $coddepto.$codmunip;
    
    $query = <<<EOF
    SELECT pp.codpartido as codpartido, pp.descripcion as descripcion, sum(dd.numvotos) as votos
    FROM PPARTIDOS pp, PCANDIDATOS  pc,       
     ( SELECT idcandidato,numvotos
       FROM DDETALLEBOLETIN
    WHERE coddivipol LIKE '$codcordivi' || '%' ) dd 
    WHERE pp.codpartido = pc.codpartido AND pc.idcandidato = dd.idcandidato
    GROUP BY pp.codpartido, pp.descripcion
    ORDER BY votos DESC
EOF;
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();
    
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($codcordivi,$codnivel);

    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    $nomDivipol = "";
    foreach($resultDivipol as $row){
        $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>