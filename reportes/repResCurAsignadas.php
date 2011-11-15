<?php
	if($_GET['formato'] != "pdf"){
		require_once('resumenCurulesAsignadas_otr.php');
	} else {
		require_once('resumenCurulesAsignadas_pdf.php');
	}
?>