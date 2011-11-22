<?php 
    require_once('configuracionTCPDF.php');
    require_once('consolidadoPartidoCandDepto_inc.php');
    
    //////////////////////////////////////////TCPDF////////////////////////////////////////////////////////
    $pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);

    //la informacion del documento
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Luis A. Nunez');
    $pdf->SetTitle('Estadisticas Electorales');
    $pdf->SetSubject('Consolidado Partido Candidatos Nacional');
    $pdf->SetKeywords('Votacion, Partido, Consolidado, Elecciones, Colombia');

    $nomCorporacion = trim(utf8_encode($nomCorporacion));
    
    $headerstring =<<<CBC
    Consolidado Partido Candidatos Nacional
    $nomCorporacion
    $nmDepartamento $nmMunicipio $nmZona$nmComuna
    $nmPueto
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
    $header = array(utf8_encode('CÓDIGO'), 'NOMBRES', 'APELLIDOS','ELEGIDO');
    $w = array(18, 60, 60, 20); //Tamanyo de las columnas

    //Inicio Iteracion
    $pdf->SetFillColor(255, 0, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('', 'B');
    
    
    $stretch = 0;
    $suma = array_sum($w)/2;
    $pdf->Cell($suma, 6, 'Partido', 1, 0, 'C', 1,'',$stretch);
    $pdf->Cell($suma, 6, $nomPartido, 1, 0, 'C', 1,'',$stretch);
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
    
    //Bucle para mostrar los datos en el documento
    if(isset($resultInscritos)) {
        foreach ($resultInscritos as $row ) {
            $pdf->Cell($w[0], 6, str_pad($row['codcandidato'], 3, '0', STR_PAD_LEFT), 'LR', 0, 'L', $fill, '', $stretch);
            $pdf->Cell($w[1], 6, utf8_encode($row['nombres']), 'LR', 0, 'L', $fill, '', $stretch);
            $pdf->Cell($w[2], 6, utf8_encode($row['apellidos']), 'LR', 0, 'L', $fill, '', $stretch);
            $pdf->Cell($w[3], 6, ($row['elegido'] != '0')? 'SI' : 'NO', 'LR', 0, 'L', $fill, '', $stretch);
            $pdf->Ln();
            $fill=!$fill;   
        }
    }
    
    $pdf->Cell(array_sum($w), 0, '', 'T');
    
    //Guardo el documento en el servidor
    $pdf->Output('consolidadoPartidoCandNacional.pdf', 'D');
    unset($pdf);
    
?>