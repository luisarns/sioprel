<?php
    if($_GET['formato'] != "pdf"){
        require_once('consolidadoPartidoDepto_otr.php');
    } else {
        require_once('consolidadoPartidoDepto_pdf.php');
    }
?>