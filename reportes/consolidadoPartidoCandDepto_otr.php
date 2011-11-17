<?php 
    require_once('configuracionOTR.php');
    require_once('consolidadoPartidoCandDepto_inc.php');
    
    ///////////////////////////////////////////////////////////////////////////
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                 ->setLastModifiedBy("Ing. Luis A. Sanchez")
                 ->setTitle("Office 2007 XLSX Test Document")
                 ->setSubject("Office 2007 XLSX Test Document")
                 ->setDescription("Consolidado Partido Candidato Nacional")
                 ->setKeywords("office 2007 openxml")
                 ->setCategory("");
    
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode($nomCorporacion));
    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', utf8_encode($nomDivipol));
    $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', utf8_encode($nomPartido));
    $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', utf8_encode('CÓDIGO'))
            ->setCellValue('B4', 'NOMBRES')
            ->setCellValue('C4', 'APELLIDOS')
            ->setCellValue('D4', 'ELEGIDO');
    
    $cont = 5;
    while ($row = ibase_fetch_object($resultInscritos)) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont, str_pad($row->CODCANDIDATO, 3, '0', STR_PAD_LEFT));
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($row->NOMBRES));
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont, utf8_encode($row->APELLIDOS));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont, ($row->ELEGIDO != '0')? 'SI' : 'NO');
        $cont++;
    }    
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    
    switch ($_GET['formato']) {
        case 'xls':
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=consolidadoPartidoCandNacional.xls");
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        break;
        case 'doc':
            header("Content-type: application/msword");
            header("Content-Disposition: attachment; filename=consolidadoPartidoCandNacional.doc");
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
            $objWriter->setSheetIndex(0);
            $objWriter->save('php://output');
        break;
        case 'txt':
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=consolidadoPartidoCandNacional.txt");
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->setDelimiter(',');
            $objWriter->setEnclosure('');
            $objWriter->setLineEnding("\r\n");
            $objWriter->setSheetIndex(0);
            $objWriter->save('php://output');
        break;
    }
    
    //cerrar la coneccion
    ibase_free_result($resulCorporacion);
    ibase_close($firebird);
    
?>