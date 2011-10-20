<?php
	if($_GET['formato'] != "pdf"){
		require_once('consolidadoPartidoLista_otr.php');
	} else {
		require_once('consolidadoPartidoLista_pdf.php');
	}
?>