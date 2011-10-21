<?php if(!isset($_GET['consultar'])) { ?>

	<?php
		include_once('corporaciones.php');
		include_once('partidos.php');
		include_once('departamentos.php');
	?>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/valLisEleCorp.js"></script>
	
	<h3>Formulario Listado Elegidos Corporación</h3>
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	
		<!--//-->
		Corporaci&oacute;n : <select name="corporacion" onChange="mostrarDepto(this.value)" >
		<option value = "-" > - </option>
		<?php foreach($corporaciones as $corporacion){ ?>
		<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
		<?php } ?>
		</select>
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
		Sexo : <select name="sexo" >
		<option value = "-" > - </option>
		<option value = "M" > Masculino </option>
		<option value = "F" > Femenino </option>
		</select>
		<!--//-->
		
		<!-- Manejo de radio buttons -->
		
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
		<input type="hidden" name="opcion"  value="5"/>
	
	</form>

<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php 
		
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repLisElegCorp.php".$_SERVER['REQUEST_URI'];
		
		$codcorporacion = $_GET['corporacion'];
		$coddivipol = $_GET['departamento'];
		$codnivel   = 1;
		
		if(isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
			$coddivipol .= $_GET['municipio'];
			$codnivel = 2;
		}
		
		
		$tx1 = "";
		if($_GET['sexo'] != "-") {
			$tx1 = "AND pc.genero='".$_GET['sexo']."'";
		}
		
		$tx2 = "";
		if($_GET['partido'] != "-") {
			$tx2 = "AND pc.codpartido=".$_GET['partido'];
		}
		
		$tx3 = "";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$tx3 = "AND pc.idcomuna = ".$_GET['comuna'];
		}
		
		$urlReportes .="&formato=";
		
		$nivcorpo = getNivelCorporacion($codcorporacion);
		$cordivi = substr($coddivipol,0,getNumDigitos($nivcorpo));
		
		$query =<<<EOF
		SELECT pc.nombres,pc.apellidos, pp.descripcion, pe.numvotos
		FROM PCANDIDATOS pc, PELEGIDOS pe, PPARTIDOS pp
		WHERE pc.idcandidato = pe.idcandidato $tx1
		AND pc.coddivipol LIKE '$cordivi' || '%' $tx2
		AND pc.codnivel = $nivcorpo $tx3
		AND pp.codpartido = pc.codpartido
		AND pc.codcorporacion = $codcorporacion
EOF;

		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
	?>
	
	<table border="1">
		<tr>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Partido</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo htmlentities($row->NOMBRES)?></td>
				<td><?php echo htmlentities($row->APELLIDOS)?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
				<td><?php echo $row->NUMVOTOS?></td>
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
