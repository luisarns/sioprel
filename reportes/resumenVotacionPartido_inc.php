<?php 
    $coddepto = $_GET['departamento'];
    $codmunip = ($_GET['municipio'] != "-")?$_GET['municipio']:"";
    $codcordivi = $coddepto.$codmunip;

    $query =<<<EOF
        SELECT lpad(c2.codpartido,3,'0') as codpartido, c2.descripcion as descripcion, sum(c1.votos) as votos
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
    
    $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
    $result   = ibase_query($firebird,$query);
    
    include_once('../contenido/FunDivipol.php');
    $queryDivipol = getQueryDivipolCompleta($codcordivi,2);

    $resultDivipol = ibase_query($firebird, $queryDivipol);
    $nomDivipol = "";
    while($row = ibase_fetch_object($resultDivipol)){
        $nomDivipol = $nomDivipol . ' ' . $row->DESCRIPCION;
    }
    
    ibase_free_result($resultDivipol);
    
?>