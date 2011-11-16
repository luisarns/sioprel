<?php
    if($_GET['formato'] != "pdf"){
        require_once('consolidadoPartidoCandDepto_otr.php');
    } else {
        require_once('consolidadoPartidoCandDepto_pdf.php');
    }
?>