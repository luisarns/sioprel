<?php

	// $codcorpo    = $datos->codcorpo;
	// $nivcorpo    = $datos->nivcorpo;
	// $codcordiv   = substr($datos->coddivipol,0,getNumDigitos($nivcorpo));
	// $coddivcorto = substr($datos->coddivipol,0,getNumDigitos($datos->codnivel));
	
	// $firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
	
	// $query =<<<EOF
	// SELECT pp.codpartido ||'-'|| pc.codcandidato as codigo, pc.nombres, pc.apellidos, pp.descripcion, sum(mv.numvotos) as votos
    // FROM ppartidos pp, pcandidatos pc, pmesas pm, mvotos mv
    // WHERE pc.coddivipol LIKE '$codcordiv'   || '%' AND pc.codnivel = $nivcorpo AND pc.codcorporacion = $codcorpo
    // AND pm.coddivipol   LIKE '$coddivcorto' || '%' AND pm.codtransmision = mv.codtransmision
    // AND pc.idcandidato = mv.idcandidato AND pp.codpartido = pc.codpartido AND pc.codcandidato <> 0
    // GROUP BY pp.codpartido,pc.codcandidato,pc.nombres, pc.apellidos,pp.descripcion;
// EOF;
	
	// //pc.codcandidato <> 0 para seleccionar solo los candidatos evitar las listas
	
	// $result   = ibase_query($firebird,$query);
	
	// $objPHPExcel = new PHPExcel();
	
	// //Defino las propiedades
	// $objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
						 // ->setLastModifiedBy("Ing. Luis A. Sanchez")
						 // ->setTitle("Office 2007 XLSX Test Document")
						 // ->setSubject("Office 2007 XLSX Test Document")
						 // ->setDescription("Votacion Partido Municipio.")
						 // ->setKeywords("office 2005 openxml")
						 // ->setCategory("");
	
	
	// $objPHPExcel->setActiveSheetIndex(0)
            // ->setCellValue('A1', 'CODPARTIDO')
            // ->setCellValue('B1', 'DESCRIPCION')
            // ->setCellValue('C1', 'VOTOS');
			
	// //Agregando los valores al informe
	// $cont = 2;
	// while($row = ibase_fetch_object($result)) {
		// $objPHPExcel->getActiveSheet()->setCellValue('A'.$cont,$row->CODPARTIDO);
		// $objPHPExcel->getActiveSheet()->setCellValue('B'.$cont,utf8_encode($row->DESCRIPCION));
		// $objPHPExcel->getActiveSheet()->setCellValue('C'.$cont,$row->VOTOS);
		// $cont++;
	// }

	// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
	// ibase_free_result($result);
	// ibase_close($firebird);
	
?>
