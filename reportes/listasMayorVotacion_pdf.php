<?php
	require_once('configuracionTCPDF.php');
	require_once('listasMayorVotacion_inc.php');
	
	//////////////////////////////////////////TCPDF////////////////////////////////////////////////////////
	$pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);
	
	//la informacion del documento
	$pdf->SetCreator('TCPDF');
	$pdf->SetAuthor('Luis A. Nunez');
	$pdf->SetTitle('Estadisticas Electorales');
	$pdf->SetSubject(utf8_encode('Listas Mayor Votación'));
	$pdf->SetKeywords('Votacion, Listas, Consolidado, Elecciones, Colombia');
	
	$header  = utf8_encode('Listas Mayor Votación');
	$nomCorporacion = trim(utf8_encode($nomCorporacion));
	$nomDivipol = trim(utf8_encode($nomDivipol));
	
    $headerstring =<<<CBC
    $header
    $nomCorporacion
    $nomDivipol
CBC;
	
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
	$pdf->SetFont('helvetica', '', 10);
	
	//Adiciono una pagina
	$pdf->AddPage();
	
	//Cabeceras de las columnas
	$header = array('CODIGO','LISTA','VOTOS');
	$w = array(18, 100, 18); //Tamanyo de las columnas
	
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
	
	$fill = 0;
        if (isset ($result)) {
            foreach($result as $row) {
                $pdf->Cell($w[0], 6, str_pad($row['codpartido'], 3, '0', STR_PAD_LEFT), 'LR', 0, 'L', $fill,'',$stretch);
                $pdf->Cell($w[1], 6, utf8_encode($row['descripcion']), 'LR', 0, 'L', $fill,'',$stretch);
                $pdf->Cell($w[2], 6, number_format($row['votos']), 'LR', 0, 'R', $fill,'',$stretch);
                $pdf->Ln();
                $fill=!$fill;
            }
        }
	$pdf->Cell(array_sum($w), 0, '', 'T');
	
	//Creando el escritor
	$pdf->Output('listasMayorVotacion.pdf', 'D');
	unset($pdf);
	
?>