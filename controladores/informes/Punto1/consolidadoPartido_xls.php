<?php
	session_start();
	$params = unserialize($_SESSION['consolidadoPartido']);
	echo 'coddivipol : '.$params->coddivipol.'<br/>';
	echo 'codnivel :'.$params->codnivel.'<br/>';
	echo 'descripci&oacute;n :'.$params->descripcion.'<br/>';
?>