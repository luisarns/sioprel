<?php
	if($_GET['formato'] != "pdf"){
		require_once('elegidosAsignacionCurules_otr.php');
	} else {
		require_once('elegidosAsignacionCurules_pdf.php');
	}
?>