<?php
	if($_GET['formato'] != "pdf"){
		require_once('listasMayorVotacion_otr.php');
	} else {
		require_once('listasMayorVotacion_pdf.php');
	}
?>