<?php if(!isset($_GET['consultar'])) { ?>

	<?php
		include_once('corporaciones.php');
		include_once('departamentos.php');
	?>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/resVotCand.js"></script>
	
	<h3>Formulario Resumen Votación Candidato</h3>
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	
		<!--//-->
		Corporaci&oacute;n : <select name="corporacion" onChange="mostrarDepto(this.value)" >
		<option value = "-" > - </option>
		<?php foreach($corporaciones as $corporacion){ ?>
		<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
		<?php } ?>
		</select>
		<!--//-->
		
		<div id="divseldepto" style="display:none;">
		Departamento : <select name="departamento" onChange="cargarMunicipios(this.value)">
		<option value = "-" > - </option>
		<?php foreach($departamentos as $departamento) { ?>
		<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
		<?php } ?>
		</select>
		</div>
		
		<div id="divselmunicipio" style="display:none;">
		Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
		<option value = "-" > Seleccione un departamento </option> </select>
		<!--//-->
		</div>
		
		<div id="divselcomuna" style="display:none;">
		Comuna : <select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
		<option value = "-" > Seleccione un municipio </option> </select>
		<!--//-->
		</div>
		<br/>
		
		<!-- Los votones para envio de los datos al servidor -->
		<input type="submit" name="consultar" value="Consultar"/><input type="reset" name="limpiar" value="Limpiar" />
		<input type="hidden" name="opcion"  value="3"/>
	
	</form>

<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php 
		
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repResVotaCandidato.php".$_SERVER['REQUEST_URI'];
		
		$codcorporacion = $_GET['corporacion'];
		$coddepto = $_GET['departamento'];
		$codmunip = $_GET['municipio'];
		
		$txt = "";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$txt = "AND pc.idcomuna = ".$_GET['comuna'];
			$txt .= " AND pd.idcomuna = ".$_GET['comuna'];
		}
		
		$urlReportes .="&formato=";
		
		$codcordivi = $coddepto."".$codmunip;
		$nivcorpo = getNivelCorporacion($codcorporacion);
		$cordivi = substr($codcordivi,0,getNumDigitos($nivcorpo));
		
		$query =<<<EOF
		SELECT pc.codpartido || '-' || pc.codcandidato as codigo  ,pc.nombres, pc.apellidos ,sum(mv.numvotos) as votos
		FROM pmesas pm, mvotos mv, pcandidatos pc, pdivipol pd
		WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
		AND pd.coddivipol LIKE '$codcordivi' || '%' $txt
		AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
		AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
		AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
		ORDER BY pc.codpartido, pc.codcandidato
EOF;

		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
	?>
	
	<table border="1">
		<tr>
			<th>Código</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODIGO?></td>
				<td><?php echo htmlentities($row->NOMBRES)?></td>
				<td><?php echo htmlentities($row->APELLIDOS)?></td>
				<td><?php echo $row->VOTOS?></td>
			</tr>
		<?php } ?>
		<tr>
		<table>
			<tr>
			<td><h4>Descargar</h4></td>
			<td><a href="<?php echo $urlReportes."pdf"?>" target="_BLANK"><img src="images/logo_pdf.png"  alt="pdf" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."xls"?>" target="_BLANK"><img src="images/logo_xls.jpg"  alt="xls" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."doc"?>" target="_BLANK"><img src="images/logo_doc.jpg"  alt="doc" height="50" width="50" /></a><td>
			<td><a href="<?php echo $urlReportes."txt"?>" target="_BLANK"><img src="images/logo_text.jpg" alt="txt" height="50" width="50" /></a><td>
			</tr>
		</table>
		</tr>
	</table>
	
	<?php 
		ibase_free_result($result);
		ibase_close($firebird);
	?>
	
<?php } ?>
