<?php
    require_once('configuracionOTR.php');
    require_once('consolidadoPartidoLista_inc.php');
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                         ->setLastModifiedBy("Ing. Luis A. Sanchez")
                         ->setTitle("Office 2007 XLSX Test Document")
                         ->setSubject("Office 2007 XLSX Test Document")
                         ->setDescription("Consolidado Partido Lista")
                         ->setKeywords("office 2007 openxml")
                         ->setCategory("");

    //Editando
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', utf8_encode($nomCorporacion));
    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', $nmDepartamento.' '.$nmMunicipio .' '. $nmZona.''.$nmComuna);
    $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', $nmPueto.' '.$nmMesa);
    $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
    //Editando

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', utf8_encode('Participación'))
        ->setCellValue('B4', $participacion . '%')
        ->setCellValue('A5', utf8_encode('Abstención'))
        ->setCellValue('B5', $asbtencion . '%')
        ->setCellValue('C4', utf8_encode('Potencial'))
        ->setCellValue('D4', number_format($potencial));

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A6', utf8_encode('CÓDIGO'))
        ->setCellValue('B6', 'NOMBRE')
        ->setCellValue('C6', 'VOTOS')
        ->setCellValue('D6', utf8_encode('PARTICIPACIÓN'));

    //Configurado del contenido
    $cont = 7;
    foreach($partidos as $partido) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($partido['codigo'], 3, '0', STR_PAD_LEFT));
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, $partido['descripcion']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($partido['votos']));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, round($partido['votos']*100/$potencial,2) . '%');
        $cont++;

    }
    
    foreach ($votacionEspecial as $votoEsp) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,'');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, $votoEsp['descripcion']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($votoEsp['votos']));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, round($votoEsp['votos']*100/$potencial,2) . '%');
        $cont++;
    }

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    //Fin del contenido

    //Creando el escritor en funcion del formato al que se quiera exportar el documento
    switch ($_GET['formato']) {
        case 'xls':
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=consolidadoPartidoLista.xls");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        break;
        case 'doc':
                header("Content-type: application/msword");
                header("Content-Disposition: attachment; filename=consolidadoPartidoLista.doc");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
                $objWriter->setSheetIndex(0);
                $objWriter->save('php://output');
        break;
        case 'txt':
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=consolidadoPartidoLista.txt");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
                $objWriter->setDelimiter(',');
                $objWriter->setEnclosure('');
                $objWriter->setLineEnding("\r\n");
                $objWriter->setSheetIndex(0);
                $objWriter->save('php://output');
        break;
    }

?>