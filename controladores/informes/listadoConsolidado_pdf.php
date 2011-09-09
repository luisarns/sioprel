<?php 
	session_start(); 
	header("Content-type: application/pdf");
	header("Content-Disposition: attachment; filename=informe.pdf");
	header('Cache-Control: max-age=0');
	
	error_reporting(E_ALL);
	
	//Inclusion de la clase y los parametros para usar la DB
	require_once '../../SPSQLiteClass-0.6/SPSQLite.class.php';
	require_once '../FunDivipol.php';
	define('PATH_DB','../../../dbdir/');
	
	$coddivipol     = $_SESSION['coddivipol'];
	$codnivel       = $_SESSION['codnivel'];
	$codcorporacion = $_SESSION['codcorporacion'];
	$corpnivel      = $_SESSION['corpnivel'];
	$coddivcorto    = substr($coddivipol,0,getNumDigitos($codnivel));
	
	$codcordiv      = str_pad(substr($coddivipol,0,getNumDigitos($corpnivel)),9,'0');
	
	$texto1 = "";
	$texto2 = "";
	
	$texto1 .= (isset($_SESSION['idpartido']))?"AND pc.codpartido = ".$_SESSION['idpartido']." ":"";
	$texto1 .= (isset($_SESSION['idlista']))?"AND pc.tipolista = ".$_SESSION['idlista']." ":"";
	$texto2 .= (isset($_SESSION['idmesa']))?"AND pm.codtransmision = ".$_SESSION['idmesa']." ":"";
	
	$mesaQuery = "pmesas";
	
	if(isset($_SESSION['idcomuna'])){ 
		$idcomuna = $_SESSION['idcomuna'];
		$mesaQuery =<<<EOF
		(SELECT m.codtransmision as codtransmision, m.coddivipol as coddivipol, m.codcorporacion as codcorporacion
		FROM pdivipol pd, pmesas m
		WHERE pd.coddivipol = m.coddivipol AND 
		pd.coddivipol LIKE '$coddivcorto' || '%' AND pd.codnivel = 4
		AND pd.idcomuna = $idcomuna AND m.codcorporacion = $codcorporacion)
EOF;
	}
	$sqlite = new SPSQLite(PATH_DB . 'elecciones2011.db');
	$query =<<<EOF
	SELECT 'ALCALDIA' corporacion,1 lista,ppc.descripcion as partido,sum(pmv.totalVotos) as votos
	FROM (SELECT pp.descripcion,pc.idcandidato 
			FROM ppartidos pp, pcandidatos pc
			WHERE pp.codpartido = pc.codpartido 
			AND pc.coddivipol = '$codcordiv' AND pc.codnivel = $corpnivel
			AND pc.codcorporacion = $codcorporacion $texto1) ppc,
		(SELECT mv.idcandidato,sum(mv.numvotos) as totalVotos
			FROM $mesaQuery pm, mvotos mv
			WHERE pm.coddivipol LIKE '$coddivcorto' || '%' AND pm.codcorporacion = $codcorporacion 
			AND mv.codtransmision = pm.codtransmision $texto2 GROUP BY mv.idcandidato ORDER BY mv.idcandidato) pmv
	WHERE ppc.idcandidato = pmv.idcandidato
	GROUP BY ppc.descripcion
EOF;
	
	$sqlite->query($query);
	$rows = $sqlite->returnRows('assoc');
	$numRows = $sqlite->numRows();
	
	//Inclusion de la clase para construir el archivo de excell
	require_once '../PHPExcell1.7.6/Classes/PHPExcel.php';
	
	$objPHPExcel = new PHPExcel();
	
	//Defino las propiedades
	$objPHPExcel->getProperties()->setCreator("Ing. Luis A. Sanchez")
							 ->setLastModifiedBy("Ing. Luis A. Sanchez")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Listado Consolidado por Partido.")
							 ->setKeywords("office 2007 openxml")
							 ->setCategory("");
	
	//Arreglo de estilos
	//Definicion del arreglo de estilo para los bordes de la tabla del pdf
	$styleArray = array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '000000')
		)
	);
	
	//Adicionando datos
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CORPORACION')
            ->setCellValue('B1', 'LISTA')
            ->setCellValue('C1', 'PARTIDO')
            ->setCellValue('D1', 'VOTOS');
			
	$i = 2;
	if($numRows > 1 ){
		foreach($rows as $row) {
			$objPHPExcel->getActiveSheet()->setCellValue("A".$i, $row['corporacion']);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$i, $row['lista']);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$i, $row['partido']);
			$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $row['votos']);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getBorders()->applyFromArray($styleArray);
			$i++;
		}
	} else if ($numRows == 1){
		$objPHPExcel->getActiveSheet()->setCellValue("A".$i, $rows['corporacion']);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$i, $rows['lista']);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$i, $rows['partido']);
		$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $rows['votos']);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getBorders()->applyFromArray($styleArray);
	}
	
	//para cambiar el borde de las celdas que contienen el titulo
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	
	//Cambiar el tamano de la celda manualmente
	$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getBorders()->applyFromArray($styleArray);
	
	
	//Creando el escritor
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	$objWriter->setSheetIndex(0);
	
	//Buscar un metodo para enviar al cliente el archivo
	$objWriter->save('php://output');
	
	//cierro la base de datos 
	$sqlite->close();
	unset($sqlite);
?>
<a href="<?php echo "listaConsolidado.pdf" ?>">Descargar</a>