<?php
    require_once('configuracionOTR.php');
    require_once('consolidadoPartidoDepto_inc.php');
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                 ->setLastModifiedBy("Ing. Luis A. Sanchez")
                 ->setTitle("Office 2007 XLSX Test Document")
                 ->setSubject("Office 2007 XLSX Test Document")
                 ->setDescription("Consolidado Partido Nacional")
                 ->setKeywords("office 2007 openxml")
                 ->setCategory("");
    
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode($nomCorporacion));
    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', utf8_encode($nomDivipol));
    $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', utf8_encode('CÓDIGO'))
            ->setCellValue('B3', 'PARTIDO')
            ->setCellValue('C3', 'No.AVALADOS')
            ->setCellValue('D3', 'No.ELEGIDOS')
            ->setCellValue('E3', 'VOTOS');
    
    $cont = 4;
    foreach ($votosPartido as $votoPartido) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($votoPartido['codpartido'], 3, '0', STR_PAD_LEFT));
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($votoPartido['descripcion']));
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, number_format($votoPartido['avalados']));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, number_format($votoPartido['elegidos']));
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$cont, number_format($votoPartido['votos']));
        $cont++;
    }
    
    switch($_GET['formato']) {
        case 'xls':
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=consolidadoPartidoNacional.xls");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        break;
        case 'doc':
                header("Content-type: application/msword");
                header("Content-Disposition: attachment; filename=consolidadoPartidoNacional.doc");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
                $objWriter->setSheetIndex(0);
                $objWriter->save('php://output');
        break;
        case 'txt':
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=consolidadoPartidoNacional.txt");
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