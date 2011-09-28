<?php 
	session_start(); 
	unset($_SESSION['idcomuna']);
	unset($_SESSION['idpartido']);
	unset($_SESSION['idlista']);
	unset($_SESSION['idmesa']);
	
	$_SESSION['coddivipol'] = $_POST['coddivipol'];
	$_SESSION['codnivel'] = $_POST['codnivel'];
	$_SESSION['codcorporacion'] = $_POST['codcorporacion'];
	$_SESSION['corpnivel'] = $_POST['corpnivel'];
	
	if(isset($_POST['idpartido'])){
		$_SESSION['idpartido'] = $_POST['idpartido'];
	}
	
	if(isset($_POST['idlista'])){
		$_SESSION['idlista'] = $_POST['idlista'];
	}

	if(isset($_POST['idmesa'])){
		$_SESSION['idmesa'] = $_POST['idmesa'];
	}
	
	if(isset($_POST['idcomuna'])){
		$_SESSION['idcomuna'] = $_POST['idcomuna'];
	}
	
	$salida = array("success" => true, "msg" => "Consultando consolidado..." );
	echo json_encode($salida);
	
	//aqui hacer la consulta de los datos crear los archivos con la configuracion
	
?>
