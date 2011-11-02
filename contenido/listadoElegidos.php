<?php
	include_once('corporaciones.php');
	include_once('partidos.php');
	include_once('departamentos.php');
?>

<script type="text/javascript" src="js/valLisEleCorp.js"></script>

<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="5%" background="../images/ds_comp_bars_gral.jpg">
		<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
		</td>
		<td width="83%" background="../images/ds_comp_bars_gral.jpg">
			<strong>Formulario Elegidos Corporación</strong>
		</td>
		<td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
			<img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
		</td>
	</tr>
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td class="regOscuro" align="left">
			<STRONG>&nbsp;</STRONG>
		</td>
	</tr>
	</table>
	
	<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave">
		
		<tr>
			<td class="regSuaveleft" >Corporaci&oacute;n :</td>
			<td class="regSuaveleft">
				<select name="corporacion" onChange="mostrarDepto(this.value)" >
				<option value = "-" >-Ninguna-</option>
				<?php foreach($corporaciones as $corporacion){ ?>
				<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
				<?php } ?>
				</select>
			</td>
			
			<td class="regSuaveleft">Sexo :</td>
			<td class="regSuaveleft">
				<select name="sexo" >
					<option value = "-" >-Ninguna-</option>
					<option value = "M" > Masculino </option>
					<option value = "F" > Femenino </option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="regSuaveleft" >Partido :</td>
			<td class="regSuaveleft" colspan="3">
				<select name="partido" >
					<option value = "-" >-Ninguna-</option>
					<?php foreach($partidos as $partido){ ?>
					<option value=<?php echo $partido['id'] ?> > <?php echo $partido['nombre'] ?> </option>
					<?php } ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="regSuaveleft" colspan="2">
				<div id="divseldepto" style="display:none;">
				Departamento : <select name="departamento" onChange="cargarMunicipios(this.value)">
				<option value = "-" >-Ninguna-</option>
				<?php foreach($departamentos as $departamento) { ?>
				<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
				<?php } ?>
				</select>
				</div>
			</td>
			<td class="regSuaveleft" colspan="2">
				<div id="divselmunicipio" style="display:none;">
				Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
				<option value = "-" > Seleccione un departamento </option> </select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="regSuaveleft" colspan="3">
				<div id="divselcomuna" style="display:none;">
				Comuna : <select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
				<option value = "-" > Seleccione un municipio </option> </select>
				</div>
			</td>
			<td class="regSuaveleft">&nbsp;</td>
		</tr>
		
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td align="center" class='regOscuroCenter'>   
				<input type="submit" class="hospital" name="consultar" value="Consultar"/>
			</td>
		</tr>
	</table>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>

			<td background="../images/ds_comp_bari_gral.jpg">
				<img src="../images/ds_comp_izq_bari_gral.jpg" width="25" height="25">
			</td>
			<td background="../images/ds_comp_bari_gral.jpg">&nbsp;</td>
			<td align="right" background="../images/ds_comp_bari_gral.jpg">
				<img src="../images/ds_comp_der_bari_gral.jpg" width="25" height="25">
			</td>
				
		</tr>
	</table>
	
	<input type="hidden" name="opcion"  value="5"/>

</form>

<div id="tbLisElegidos"></div>