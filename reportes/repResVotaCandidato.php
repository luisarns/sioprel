<?php
	if($_GET['formato'] != "pdf"){
		require_once('resumenVotacionCandidato_otr.php');
	} else {
		require_once('resumenVotacionCandidato_pdf.php');
	}
?>