<?php
	session_start();
	require_once 'ConfiguracionTCPDF.php';
	
	$datos = unserialize($_SESSION['consolidadoPartidoLista']);
	require_once('consolidadoPartidoLista_inc.php');
	
	$partidos = array();
	$candidatos = array();
	
	while($row = ibase_fetch_object($result)) {
		array_push($partidos,$row);
	}
	if($result1 != null){
		while($row = ibase_fetch_object($result1)) {
			array_push($candidatos,$row);
		}
	}
	
	//////////////////////////////////////////TCPDF////////////////////////////////////////////////////////
	$pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);
	
	//la informacion del documento
	$pdf->SetCreator('TCPDF');
	$pdf->SetAuthor('Luis A. Nunez');
	$pdf->SetTitle('Estadisticas Electorales');
	$pdf->SetSubject('Consolidado Partido Listas');
	$pdf->SetKeywords('Votacion, Partido, Consolidado, Elecciones, Colombia');
	
	$headerstring = utf8_encode("Consolidado Partido y Listas");
	$pdf->SetHeaderData($pathLogo, $logowidth, $headertitle, $headerstring);
	
	//La fuente para la cabecera y pie de pagina
	$pdf->setHeaderFont(Array($fontmain, '', $fontmainsize));
	$pdf->setFooterFont(Array($fontdata, '', $fontdatasize));
	
	// Fuente por defecto
	$pdf->SetDefaultMonospacedFont($fontmnspace);
	
	//Los margenes de las pagina, cabecera y pie de pagina
	$pdf->SetMargins($marginleft, $margintop, $marginright);
	$pdf->SetHeaderMargin($marginheader);
	$pdf->SetFooterMargin($marginfooter);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, $marginbottom);

	//set image scale factor
	$pdf->setImageScale($imgscalert);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	// asigno la fuente del documento
	$pdf->SetFont('helvetica', '', 12);
	
	//Adiciono una pagina
	$pdf->AddPage();
	
	//Cabeceras de las columnas
	$header = array('CODIGO', 'NOMBRE', 'VOTOS');
	$w = array(18,100,18); //Tamanyo de las columnas
	
	//Inicio Iteracion
	$pdf->SetFillColor(255, 0, 0);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128, 0, 0);
	$pdf->SetLineWidth(0.3);
	$pdf->SetFont('', 'B');
	
	//Ajusto el texto a la celda con la opcion stretch o redusco el tamanyo
	$stretch = 0;
	
	// Header
	$num_headers = count($header);
	for($i = 0; $i < $num_headers; ++$i) {
		$pdf->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 1,'',$stretch);
	}
	$pdf->Ln();
	
	//Datos
	$pdf->SetFillColor(224, 235, 255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',8);
	
	foreach($partidos as $partido) {
		$pdf->Cell($w[0], 6, $partido->CODIGO, 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Cell($w[1], 6, utf8_encode($partido->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Cell($w[2], 6, $partido->VOTOS, 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Ln();
		$fill=!$fill;
		foreach($candidatos as $candidato) {
			if($candidato->CODPARTIDO == $partido->CODIGO) {
				$pdf->Cell($w[0], 6, $partido->CODIGO.'-'.$candidato->CODCANDIDATO, 'LR', 0, 'L', $fill,'',$stretch);
				$pdf->Cell($w[1], 6, utf8_encode($candidato->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
				$pdf->Cell($w[2], 6, $candidato->VOTOS, 'LR', 0, 'L', $fill,'',$stretch);
				$pdf->Ln();
			}
		}
		$fill=!$fill;
	}
	$pdf->Cell(array_sum($w), 0, '', 'T');
	
	ibase_free_result($result);
	if($result1 != null){ibase_free_result($result1);}
	ibase_close($firebird);
	
	//Guardo el documento en el servidor
	$pdf->Output('consolidadoPartidoLista.pdf', 'D');
	unset($pdf);
?>