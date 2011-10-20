<?php if(!isset($_GET['consultar'])) { ?>

	<?php
		include_once('departamentos.php');
	?>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/resVotPart.js"></script>
	
	<h3>Formulario Resumen Votación Partido</h3>
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	
		
		Departamento : <select name="departamento" onChange="cargarMunicipios(this.value)">
		<option value = "-" > - </option>
		<?php foreach($departamentos as $departamento) { ?>
		<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
		<?php } ?>
		</select>
		
		<div id="divselmunicipio" style="display:none;">
		Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
		<option value = "-" > Seleccione un departamento </option> </select>
		<!--//-->
		</div>
		<br/>
		
		<!-- Los votones para envio de los datos al servidor -->
		<input type="submit" name="consultar" value="Consultar"/><input type="reset" name="limpiar" value="Limpiar" />
		<input type="hidden" name="opcion"  value="4"/>
	
	</form>

<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php 
		
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repResVotaPartido.php".$_SERVER['REQUEST_URI'];
		
		$coddepto = $_GET['departamento'];
		$codmunip = ($_GET['municipio'] != "-")?$_GET['municipio']:"";
		$codcordivi = $coddepto.$codmunip;
		
		$urlReportes .="&formato=";
		
		$query =<<<EOF
		SELECT c2.codpartido as codpartido, c2.descripcion as descripcion, sum(c1.votos) as votos
		FROM 
		   (SELECT mv.idcandidato,sum(mv.numvotos) as votos
			FROM pmesas pm, mvotos mv
			WHERE pm.codtransmision = mv.codtransmision
			AND pm.coddivipol LIKE '$codcordivi' || '%'
			GROUP BY mv.idcandidato) c1,
		   (SELECT pp.codpartido,pp.descripcion,pc.idcandidato
			FROM ppartidos pp, pcandidatos pc
			WHERE pc.codpartido = pp.codpartido) c2
		WHERE c1.idcandidato = c2.idcandidato
		GROUP BY c2.codpartido,c2.descripcion ORDER BY c2.codpartido
EOF;

		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
	?>
	
	<table border="1">
		<tr>
			<th>Código</th>
			<th>Nombre</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODPARTIDO?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
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
