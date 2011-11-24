<?php 
    $coddepto = $_GET['departamento'];
    $codmunip = ($_GET['municipio'] != "-")?$_GET['municipio']:"";
    $codcordivi = $coddepto.$codmunip;

    $query =<<<EOF
        SELECT c2.codpartido as codpartido, c2.descripcion as descripcion, sum(c1.votos) as votos
        FROM
        (SELECT mv.idcandidato,sum(mv.numvotos) as votos
            FROM pmesas pm, mvotos mv
            WHERE pm.codtransmision = mv.codtransmision
            AND pm.coddivipol LIKE '$codcordivi' || '%'
            GROUP BY mv.idcandidato) c1,
        (SELECT pp.codpartido,pp.descripcion,pc.idcandidato
            FROM ppartidos pp, pcandidatos pc
            WHERE pc.codpartido = pp.codpartido) c2
        WHERE c1.idcandidato = c2.idcandidato
        GROUP BY c2.codpartido,c2.descripcion ORDER BY c2.codpartido
EOF;
    
    $sqlite = new SPSQLite($pathDB);
    $sqlite->query($query);
    $result = $sqlite->returnRows();
    
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($codcordivi,2);

    $sqlite->query($queryDivipol);
    $resultDivipol = $sqlite->returnRows();
    $nomDivipol = "";
    foreach($resultDivipol as $row){
        $nomDivipol = $nomDivipol . ' ' . $row['descripcion'];
    }
    
    $sqlite->close(); 
    unset($sqlite);
    
?>