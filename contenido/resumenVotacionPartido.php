<?php
	include_once('departamentos.php');
?>

<script type="text/javascript" src="js/resVotPart.js"></script>

<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="5%" background="../images/ds_comp_bars_gral.jpg">
		<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
		</td>
		<td width="83%" background="../images/ds_comp_bars_gral.jpg">
			<strong>Resumen Votación Partido </strong>
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
		<td class="regSuaveleft" >Departamento :</td>
		<td class="regSuaveleft">
			<select name="departamento" onChange="cargarMunicipios(this.value)">
			<option value = "-" > -Ninguna- </option>
			<?php foreach($departamentos as $departamento) { ?>
			<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
			<?php } ?>
			</select>
		</td>
		
		<td class="regSuaveleft" colspan="2">
			<div id="divselmunicipio" style="display:none;">
			Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
			<option value = "-" > Seleccione un departamento </option> </select>
			</div>
		</td>
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
	
	<input type="hidden" name="opcion"  value="4"/>

</form>

<div id="tbResVotPar"></div>