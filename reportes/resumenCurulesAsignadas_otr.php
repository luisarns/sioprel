<?php
    require_once('configuracionOTR.php');
    require_once('resumenCurulesAsignadas_inc.php');

    //Defino las propiedades
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
                                             ->setLastModifiedBy("Ing. Luis A. Sanchez")
                                             ->setTitle("Office 2007 XLSX Test Document")
                                             ->setSubject("Office 2007 XLSX Test Document")
                                             ->setDescription("Resumen Curules Asignadas.")
                                             ->setKeywords("office 2005 openxml")
                                             ->setCategory("");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', utf8_encode($nomCorporacion));
    $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', utf8_encode($nomDivipol));
    $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A3', 'CODIGO')
        ->setCellValue('B3', 'PARTIDO')
        ->setCellValue('C3', 'No.CURULES')
        ->setCellValue('D3', 'VOTOS');

    $cont = 4;
    while($row = ibase_fetch_object($result)) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODIGO);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->DESCRIPCION));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,utf8_encode($row->NUMCURULES));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,number_format($row->VOTOS));
            $cont++;
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


    ibase_free_result($result);
    ibase_close($firebird);


    switch($_GET['formato']){
        case 'xls':
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=ResumenCurulesAsignadas.xls");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        break;
        case 'doc':
                header("Content-type: application/msword");
                header("Content-Disposition: attachment; filename=ResumenCurulesAsignadas.doc");
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
                $objWriter->setSheetIndex(0);
                $objWriter->save('php://output');
        break;
        case 'txt':
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=ResumenCurulesAsignadas.txt");
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