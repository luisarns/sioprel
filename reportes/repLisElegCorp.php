<?php
	if($_GET['formato'] != "pdf"){
		require_once('listElegidoCorporacion_otr.php');
	} else {
		require_once('listElegidoCorporacion_pdf.php');
	}
?>