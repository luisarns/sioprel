<?php if(!isset($_GET['consultar'])) { ?>
	<?php
		include_once('corporaciones.php');
		include_once('partidos.php');
		include_once('departamentos.php');
	?>

	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/valConParLis.js"> </script>

	<h3>Formulario Consolidado Partido Lista</h3>
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
		
		<!--//-->
		Corporaci&oacute;n : <select name="corporacion" onChange="mostrarOcultarDepto(this.value)">
		<option value = "-" > - </option>
		<?php foreach($corporaciones as $corporacion){ ?>
		<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
		<?php } ?>
		</select>
		<!--//-->
		
		<!--//-->
		Detallado : <input type="checkbox" value="1" name="detallado"/>
		<!--//-->
		<br/>
		
		<!--//-->
		Partido : <select name="partido" >
		<option value = "-" > - </option>
		<?php foreach($partidos as $partido){ ?>
		<option value=<?php echo $partido['id'] ?> > <?php echo $partido['nombre'] ?> </option>
		<?php } ?>
		</select>
		<!--//-->
		
		
		<!--//-->
		<div id="combDepartamento" style="display:none;">
		Departamento : <select name="departamento" onChange="cargarMunicipios(this.value)">
		<option value = "-" > - </option>
		<?php foreach($departamentos as $departamento) { ?>
		<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
		<?php } ?>
		</select>
		</div>
		<!--//-->
		
		
		<div id="divselmunicipio" style="display:none;">
		Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
		<option value = "-" > Seleccione un departamento </option> </select>
		<!--//-->
		</div>
		
		<!--//-->
		<div id="divselzona" style="display:none;"></div>
		
		<div id="divselcomuna" style="display:none;">
		Comuna : <select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
		<option value = "-" > Seleccione un municipio </option> </select>
		<!--//-->
		</div>
		
		<div id="divselpuesto" style="display:none;">
		Puesto : <select id="selpuesto" name="puesto" onChange="alert('Cambio el puesto')">
		<option value = "-" > Seleccione un zona o comuna </option> </select>
		</div>
		
		<div id="divselmesa" style="display:none;">
		Mesa : <select id="selmesa" name="mesa" onChange="alert('Cambio la mesa')">
		<option value = "-" > Seleccione un puesto</option> </select>
		</div>
		<br/>
		
		<input type="submit" name="consultar" value="Consultar"/><input type="reset" name="limpiar" value="Limpiar" />
		<!-- Pensar en la posibilidad de una tabla para organizar los campos -->
		<input type="hidden" name="opcion"  value="1"/>
		
	</form>
<?php } else if ( $_GET['consultar'] == "Consultar") { ?>

	<?php
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repConPartidolista.php?";
		
		$codcorporacion = $_GET['corporacion'];
		$nivcorpo  = getNivelCorporacion($codcorporacion);
		
		$urlReportes.="codcorporacion=$codcorporacion";
		
		//Codigo para generar el coddivipol
		$coddivipol = $_GET['departamento'];
		$codnivel   = 1;
		
		
		if(isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
			$coddivipol .= $_GET['municipio'];
			$codnivel = 2;
			
			if(isset($_GET['zona']) && $_GET['zona'] != "-" ){
				$coddivipol .= $_GET['zona'];
				$codnivel = 3;
			}
			
			if(isset($_GET['puesto']) && $_GET['puesto'] !="-"){
				$coddivipol = $_GET['puesto'];
				$codnivel = 4;
			}
		}
		
		$urlReportes.="&nivcorpo=$nivcorpo&coddivipol=$coddivipol&codnivel=$codnivel&opcion=1";
		
		$codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));
		
		$texto1 = " ";
		if(isset($_GET['mesa']) && $_GET['mesa'] != "-"){
			$texto1 = " AND pm.codtransmision = '".$_GET['mesa']."'";
			$urlReportes.="&codtransmision=".$_GET['mesa'];
		}
		
		$texto2 ="";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$texto2 = " AND pc.idcomuna = ".$_GET['comuna'];
			$texto2 .= " AND pd.idcomuna = ".$_GET['comuna'];
			$urlReportes.="&idcomuna=".$_GET['comuna'];
		}
		
		$texto3 = "";
		$txt4 = "";
		if(isset($_GET['partido']) && $_GET['partido'] != "-"){
			$texto3 = " AND pp.codpartido = ".$_GET['partido'];
			$txt4 = "AND pc.codpartido = ".$_GET['partido'];
			$urlReportes.="&codpartido=".$_GET['partido'];
		}
		
		//Esta consulta no esta completa, hay que actualizarla
		$query =<<<EOF
		SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
		FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv, pdivipol pd
		WHERE pp.codpartido = pc.codpartido $texto1
		AND pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4
		AND pm.coddivipol = pd.coddivipol
		AND pm.codtransmision = mv.codtransmision $texto2
		AND pc.idcandidato = mv.idcandidato $texto3
		AND pc.coddivipol LIKE '$codcordiv'  || '%'
		AND pc.codnivel = $nivcorpo
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pp.codpartido, pp.descripcion
EOF;
		
		// echo $query."<br/>";
		
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
		$result1 = null;
		$query1 = null;
		if(isset($_GET['detallado']) && $_GET['detallado'] == 1) {
			$query1 =<<<EOR
			SELECT pc.codpartido,pc.codcandidato, pc.nombres ||' '|| CASE WHEN pc.codcandidato = 0 THEN '(LISTA)' ELSE pc.apellidos END as descripcion, SUM(mv.numvotos) as votos
			FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
			WHERE pm.codtransmision = mv.codtransmision $texto1
			AND pc.idcandidato = mv.idcandidato $texto2
			AND pc.coddivipol LIKE '$codcordiv' || '%'
			AND pm.coddivipol LIKE '$coddivipol'  || '%'
			AND pm.codcorporacion = $codcorporacion $txt4
			AND pc.codnivel = $nivcorpo
			GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
			$result1 = ibase_query($firebird,$query1);
			$urlReportes.="&detallado=".$_GET['detallado'];
		}
			
		$urlReportes.="&formato=";
		
		$candidatos = array();
		if($result1 != null){
			while($row = ibase_fetch_object($result1)) {
				array_push($candidatos,$row);
			}
		}
		
	?>

	<!-- En esta parte incluyo el codigo necesario para mostrar la tabla con los datos de la consulta -->
	<!-- Adicionar los links para hacer la descarga de los correspondientes reportes -->
	<table border="1">
		<tr>
			<th>Codigo</th>
			<th>Nombre</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODIGO?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
				<td><?php echo $row->VOTOS?></td>
				
			</tr>
			<?php 
				foreach($candidatos as $candidato) { 
					if($candidato->CODPARTIDO == $row->CODIGO) { ?>
						<tr>
						<td><?php echo $row->CODIGO .'-'.$candidato->CODCANDIDATO ?></td>
						<td><?php echo htmlentities($candidato->DESCRIPCION)?></td>
						<td><?php echo $candidato->VOTOS?></td>
						</tr>
			<?php }} ?>
		<?php } ?>
		<tr>
		<table>
			<tr>
			<td><h4>Descargar</h4></td>
			<td><a href="<?php echo $urlReportes."pdf"?>" target="_BLANK"><img src="images/logo_pdf.png" alt="pdf" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."xls"?>" target="_BLANK"><img src="images/logo_xls.jpg"  alt="xls" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."doc"?>" target="_BLANK"><img src="images/logo_doc.jpg"  alt="doc" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."txt"?>" target="_BLANK"><img src="images/logo_text.jpg" alt="txt" height="50" width="50" /></a><td>
			</tr>
		</table>
		</tr>
	</table>
	
	<?php 
		ibase_free_result($result);
		if($result1 != null){ibase_free_result($result1);}
		ibase_close($firebird);
	?>

<?php } ?>
