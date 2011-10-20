<?php
	if($_GET['formato'] != "pdf"){
		require_once('listadoVotacionCandidato_otr.php');
	} else {
		require_once('listadoVotacionCandidato_pdf.php');
	}
?>