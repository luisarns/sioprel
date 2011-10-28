<?php
	session_start();
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=listadoVotacionCandidato.doc");
	header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['listadoVotacionCandidato']);
	require_once 'listadoVotacionCandidato_inc.php';
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("Listado Votacion Candidato.")
						 ->setKeywords("office 2005 openxml")
						 ->setCategory("");
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO')
            ->setCellValue('B1', 'NOMBRES')
			->setCellValue('C1', 'APELLIDOS')
			->setCellValue('D1', 'PARTIDO')
            ->setCellValue('E1', 'VOTOS');
						 
	
	$cont = 2;
	while($row = ibase_fetch_object($result)){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODIGO);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->NOMBRES));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,utf8_encode($row->APELLIDOS));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,utf8_encode($row->DESCRIPCION));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$cont,$row->VOTOS);
		$cont++;
	}
	$styleArray = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
	$objPHPExcel->getActiveSheet()->getStyle('A2:E'.($cont-1))->getBorders()->applyFromArray($styleArray);
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	
	ibase_free_result($result);
	ibase_close($firebird);
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	$objWriter->setSheetIndex(0);
	
	$objWriter->save('php://output');
?>