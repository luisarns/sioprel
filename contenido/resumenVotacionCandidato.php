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
		
		<!-- Los votones para envio de los datos al servidor -->
		<input type="submit" name="consultar" value="Consultar"/><input type="reset" name="limpiar" value="Limpiar" />
		<input type="hidden" name="opcion"  value="3"/>
	
	</form>

<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php echo "Generando el listado"; ?>
	
<?php } ?>
