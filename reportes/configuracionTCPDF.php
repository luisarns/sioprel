<?php
	$host     = $_SERVER["DOCUMENT_ROOT"].'/../dbdir/siprel.gdb';
	$username = 'SYSDBA';
	$password = 'masterkey';

	//Incluir las clases para trabajar con las librerias de tcpdf
	require_once('../tcpdf/config/lang/eng.php');
	require_once('../tcpdf/tcpdf.php');
	require_once('../contenido/FunDivipol.php');
	
	//creando la cabecera para el pdf
	$page_orientacion = 'P';
	$pdf_unit = 'mm';
	$pdf_page_format = 'A4';

	//La cabecera y pie de pagina del documento
	$pathLogo = "registraduria.png";
	$logowidth = 30;
	$headertitle = utf8_encode("ESTADÍSTICAS ELECTORALES");
	
	//margenes 
	$marginleft  = 15;
	$margintop   = 27;
	$marginright = 15;
	$marginheader = 5;
	$marginfooter = 10;
	$marginbottom = 25;
	
	//escala de la imagen
	$imgscalert = 1.25;
	
	//Fuente para cabecera y pie de pagina
	$fontmain = "helvetica";
	$fontmainsize = 10;
	$fontdata = "helvetica";
	$fontdatasize = 8;
	
	//Fuente por defecto
	$fontmnspace = "courier";
	
	// $pagina = "";
	// $pagina = "";
	// $pagina = "";
	// $pagina = "";
	// $pagina = "";
	// $pagina = "";
?>