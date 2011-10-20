<?php
	if($_GET['formato'] != "pdf"){
		require_once('resumenVotacionPartido_otr.php');
	} else {
		require_once('resumenVotacionPartido_pdf.php');
	}
?>