<?php
    require_once('configuracionTCPDF.php');
    require_once('listadoVotacionCandidato_inc.php');

    //////////////////////////////////////////TCPDF////////////////////////////////////////////////////////
    //creo el nuevo documento pdf
    $pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);

    //la informacion del documento
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Luis A. Nunez');
    $pdf->SetTitle('Estadisticas Electorales');
    $pdf->SetSubject('Listado Votacion Candidato');
    $pdf->SetKeywords('Votacion, Candidatos, Listado, Elecciones, Colombia');

    $header  = utf8_encode("Listado Votación Candidato");
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
    $header = array('CODIGO','NOMBRES', 'APELLIDOS', 'PARTIDO','VOTOS');
    $w = array(18, 38, 38, 70, 18); //Tamanyo de las columnas

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
    while($row = ibase_fetch_object($result)) {
        $pdf->Cell($w[0], 6, utf8_encode($row->CODIGO), 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[1], 6, utf8_encode($row->NOMBRES), 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[2], 6, utf8_encode($row->APELLIDOS), 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[3], 6, utf8_encode($row->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
        $pdf->Cell($w[4], 6, number_format($row->VOTOS), 'LR', 0, 'R', $fill,'',$stretch);
        $pdf->Ln();
        $fill=!$fill;
    }
    $pdf->Cell(array_sum($w), 0, '', 'T');

    //Cierro la coneccion a la base de datos
    ibase_free_result($result);
    ibase_close($firebird);

    //Guardo el documento en el servidor
    $pdf->Output('listatoVotacionCandidato.pdf', 'D');
    unset($pdf);

?>