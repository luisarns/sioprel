<?php
	session_start();
	// header("Content-type: application/pdf");
	// header("Content-Disposition: attachment; filename=listadoVotacionCandidato.pdf");
	// header('Cache-Control: max-age=0');
	
	require_once 'Configuracion.php';
	$datos = unserialize($_SESSION['listadoVotacionCandidato']);
	require_once 'listadoVotacionCandidato_inc.php';
	
	// $objPHPExcel = new PHPExcel();
	
	// //usar tcpdf
	
	// //Defino las propiedades
	// $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 // ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 // ->setTitle("Office 2007 XLSX Test Document")
						 // ->setSubject("Office 2007 XLSX Test Document")
						 // ->setDescription("Listado Votacion Candidato.")
						 // ->setKeywords("office 2005 openxml")
						 // ->setCategory("");
	
	// $objPHPExcel->setActiveSheetIndex(0)
            // ->setCellValue('A1', 'CODIGO')
            // ->setCellValue('B1', 'NOMBRES')
			// ->setCellValue('C1', 'APELLIDOS')
			// ->setCellValue('D1', 'PARTIDO')
            // ->setCellValue('E1', 'VOTOS');
						 
	
	// $cont = 2;
	// while($row = ibase_fetch_object($result)){
		// $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODIGO);
		// $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->NOMBRES));
		// $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,utf8_encode($row->APELLIDOS));
		// $objPHPExcel->getActiveSheet()->setCellValue('D'.$cont,utf8_encode($row->DESCRIPCION));
		// $objPHPExcel->getActiveSheet()->setCellValue('E'.$cont,$row->VOTOS);
		// $cont++;
	// }
	// $styleArray = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
	// $objPHPExcel->getActiveSheet()->getStyle('A2:E'.($cont-1))->getBorders()->applyFromArray($styleArray);
	
	// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	
	// ibase_free_result($result);
	// ibase_close($firebird);
	
	// //Creando el escritor
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	// $objWriter->setSheetIndex(0);
	
	// $objWriter->save('php://output');
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//Uso de tcpdf y guardo el documento en la carpeta actual
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
	
	//crear la cabecera para el pdf
	$page_orientacion = 'P';
	$pdf_unit = 'mm';
	$pdf_page_format = 'A4';
	
	//creo el nuevo documento pdf
	$pdf = new TCPDF($page_orientacion, $pdf_unit, $pdf_page_format, true, 'UTF-8', false);
	
	//la informacion del documento
	$pdf->SetCreator('TCPDF');
	$pdf->SetAuthor('Luis A. Nunez');
	$pdf->SetTitle('Estadisticas Electorales');
	$pdf->SetSubject('Listado Votacion Candidato');
	$pdf->SetKeywords('Votacion, Candidatos, Listado, Elecciones, Colombia');
	
	//La cabecera y pie de pagina del documento
	$pathLogo = "../../images/registraduria.png";
	$logowidth = 30;
	$headertitle = "ESTADISTICAS ELECTORALES";
	$headerstring = "Listado Votacion Candidato";
	$pdf->SetHeaderData($pathLogo, $logowidth, $headertitle, $headerstring);
	
	//asigno la fuente de la cabecera y el pie de pagina
	$fontmain = "helvetica";
	$fontmainsize = 10;
	$fontdata = "helvetica";
	$fontdatasize = 8;
	$pdf->setHeaderFont(Array($fontmain, '', $fontmainsize));
	$pdf->setFooterFont(Array($fontdata, '', $fontdatasize));
	
	// Asigno la fuente por defecto
	$fontmnspace = "courier";
	$pdf->SetDefaultMonospacedFont($fontmnspace);
	
	//Los margenes de las paginas
	$marginleft  = 15;
	$margintop   = 27;
	$marginright = 15;
	$marginheader = 5;
	$marginfooter = 10;
	$marginbottom = 25;
	$imgscalert = 1.25;
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
	$header = array('NOMBRES', 'APELLIDOS', 'PARTIDO','VOTOS');
	
	//Inicio Iteracion
	$pdf->SetFillColor(255, 0, 0);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128, 0, 0);
	$pdf->SetLineWidth(0.3);
	$pdf->SetFont('', 'B');
	
	//para ajustar el texto a la celda
	//Hay otra opcion que es reducir el tamano del texto mostrado
	$stretch = 0;
	
	// Header
	$w = array(35, 35, 90,18);
	$num_headers = count($header);
	for($i = 0; $i < $num_headers; ++$i) {
		$pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1,'',$stretch);
	}
	$pdf->Ln();
	
	//Datos
	$pdf->SetFillColor(224, 235, 255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',8);
	// Data
	
	$fill = 0;
	while($row = ibase_fetch_object($result)) {
		$pdf->Cell($w[0], 6, utf8_encode($row->NOMBRES), 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Cell($w[1], 6, utf8_encode($row->APELLIDOS), 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Cell($w[2], 6, utf8_encode($row->DESCRIPCION), 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Cell($w[3], 6, $row->VOTOS, 'LR', 0, 'L', $fill,'',$stretch);
		$pdf->Ln();
		$fill=!$fill;
	}
	
	//Cierro la coneccion a la base de datos
	ibase_free_result($result);
	ibase_close($firebird);
	
	//Guardo el documento en el servidor
	$pdf->Output('listatoVotacionCandidato.pdf', 'D');
	//////////////////////////////////////////////////////////////////////////////////////////////////
?>