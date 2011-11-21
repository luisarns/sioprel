<?php 
    require_once('configuracionTCPDF.php');
    require_once('consolidadoPartidoLista_inc.php');

    //////////////////////////////////////////TCPDF////////////////////////////////////////////////////////
    $pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);

    //la informacion del documento
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Luis A. Nunez');
    $pdf->SetTitle('Estadisticas Electorales');
    $pdf->SetSubject('Consolidado Partido Listas');
    $pdf->SetKeywords('Votacion, Partido, Consolidado, Elecciones, Colombia');

    $header  = utf8_encode("Consolidado Partido y Listas");
    $nomCorporacion = trim(utf8_encode($nomCorporacion));
    $nomDivipol = trim(utf8_encode($nomDivipol));

    $headerstring =<<<CBC
    $header
    $nomCorporacion
    $nmDepartamento $nmMunicipio $nmZona$nmComuna
    $nmPueto $nmMesa
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
    $header = array(utf8_encode('CÓDIGO'), 'NOMBRE', 'VOTOS',utf8_encode('PARTICIPACIÓN'));
    $w = array(18,100,18,30); //Tamanyo de las columnas

    //Inicio Iteracion
    $pdf->SetFillColor(255, 0, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('', 'B');

    //Ajusto el texto a la celda con la opcion stretch o redusco el tamanyo
    $stretch = 0;
    $suma = array_sum($w)/2;
    $pdf->Cell($suma, 6, utf8_encode('Potencial'), 1, 0, 'C', 1,'',$stretch);
    $pdf->Cell($suma, 6, number_format($potencial), 1, 0, 'C', 1,'',$stretch);
    $pdf->Ln();
    $pdf->Cell($suma, 6, utf8_encode('Participación'), 1, 0, 'C', 1,'',$stretch);
    $pdf->Cell($suma, 6, $participacion . '%', 1, 0, 'C', 1,'',$stretch);
    $pdf->Ln();
    $pdf->Cell($suma, 6, utf8_encode('Abstención'), 1, 0, 'C', 1,'',$stretch);
    $pdf->Cell($suma, 6, $asbtencion . '%', 1, 0, 'C', 1,'',$stretch);
    $pdf->Ln(); 

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
    $fill = true; //Para rellenar el fondo de la fila
    foreach($partidos as $partido ) {
            $pdf->Cell($w[0], 6, str_pad($partido->CODIGO, 3, '0', STR_PAD_LEFT), 'LR', 0, 'L', $fill,'',$stretch);
            $pdf->Cell($w[1], 6, utf8_encode($partido->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
            $pdf->Cell($w[2], 6, number_format($partido->VOTOS), 'LR', 0, 'R', $fill,'',$stretch);
            $pdf->Cell($w[3], 6, round(($partido->VOTOS*100)/$potencial,2) . '%', 'LR', 0, 'R', $fill,'',$stretch);
            $pdf->Ln();
            $fill=!$fill;
            foreach($candidatos as $candidato) {
                    if($candidato->CODPARTIDO == $partido->CODIGO) {
                            $pdf->Cell($w[0], 6, str_pad($partido->CODIGO, 3, '0', STR_PAD_LEFT) . '-' . str_pad($candidato->CODCANDIDATO, 3, '0', STR_PAD_LEFT), 'LR', 0, 'L', $fill,'',$stretch);
                            $pdf->Cell($w[1], 6, utf8_encode($candidato->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
                            $pdf->Cell($w[2], 6, number_format($candidato->VOTOS), 'LR', 0, 'R', $fill,'',$stretch);
                            $pdf->Ln();
                    }
            }
            $fill=!$fill;
    }
    //Incluye la votacion especial en el pdf
    $fill = !$fill;
    foreach($votacionEspecial as $votoEsp){
        $pdf->Cell($w[0], 6, '', 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[1], 6, utf8_encode($votoEsp->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[2], 6, number_format($votoEsp->VOTOS), 'LR', 0, 'R', $fill,'',$stretch);
        $pdf->Cell($w[3], 6, round(($votoEsp->VOTOS*100)/$potencial,2) . '%', 'LR', 0, 'R', $fill,'',$stretch);
        $pdf->Ln();
    }
    $pdf->Cell(array_sum($w), 0, '', 'T');


    //Guardo el documento en el servidor
    $pdf->Output('consolidadoPartidoLista.pdf', 'D');
    unset($pdf);
	
?>