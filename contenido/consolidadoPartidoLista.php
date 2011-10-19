<?php
	include_once('corporaciones.php');
	include_once('partidos.php');
	include_once('departamentos.php');
?>

<!--Mostrar el formulario para el primer caso cuando no se a enviado la peticion -->

<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/valConParLis.js"> </script>

<h3>Formulario Consolidado Partido Lista</h3>
<form name="formPrincipal" onSubmit = "return validar(this);">
	
	<!--//-->
	Corporaci&oacute;n : <select name="corporacion" onChange="mostrarOcultarDepto(this.value)">
	<option value = "-" > - </option>
	<?php foreach($corporaciones as $corporacion){ ?>
	<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
	<?php } ?>
	</select>
	<!--//-->
	
	<!--//-->
	Detallado : <input type="checkbox" value="1" name = "detallado">
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
	
	<input type="submit" name="consultar" value="Consultar"><input type="reset" name="limpiar" value="Limpiar">
	<!-- Activar los botones de envio cuando ya esten listos todos los parametros para realizar la consulta -->
	<!-- Pensar en la posibilidad de una tabla para organizar los campos -->
	
	
</form>

<!--Mostrar la tabla en el segundo caso cuando se envio la peticion-->
<!-- Aqui abajo va el codigo necesario para contruir la tabla con los datos y los links para hacer la descarga 
	de los reportes
-->
