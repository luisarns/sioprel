<?php
	include_once('corporaciones.php');
	include_once('partidos.php');
	include_once('departamentos.php');
?>

<script type="text/javascript" src="js/valConParLis.js"> </script>

<form name="formPrincipal" method="GET" onSubmit="return validar(this);" >

	<!--Tabla imagen titulo superior -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
		<tr>
			<td width="5%" background="../images/ds_comp_bars_gral.jpg" >
				<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
			</td>
			<td width="83%" background="../images/ds_comp_bars_gral.jpg" >
				<strong>Consolidado Partido Lista </strong>
			</td>
			<td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg" >
				<img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
			</td>
		</tr>
	</table>
	
	<!--Tabla imagen gris superior -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
		<tr>
			<td class="regOscuro" align="left" >
				<STRONG>&nbsp;</STRONG>
			</td>
		</tr>
	</table>
	
	
	<!--Tabla con los campos de filtrado -->
	<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave" >
		
		<!--Fila 1 -->
		<tr>
			<td class="regSuaveleft" >Corporaci&oacute;n :</td>
			<td class="regSuave" >
				<select name="corporacion" onChange="mostrarOcultarDepto(this.value)" >
					<option value = "-" >-Ninguna-</option>
					<?php foreach($corporaciones as $corporacion) { ?>
					<option value="<?php echo $corporacion['id'] ?>" > <?php echo $corporacion['nombre'] ?> </option>
					<?php } ?>
				</select>
			</td>
			<td class="regSuaveleft" colspan="2">Detallado : <input type="checkbox" value="1" name="detallado" /> </td>
		</tr>
		<!--Fila 1 END-->
		
		<!--Fila 2 -->
		<tr>
			<td class="regSuaveleft" width="20%">Partido :</td>
			<td class="regSuave" colspan="3" width="75%">
				<select name="partido">
					<option value = "-" >-Ninguna-</option>
					<?php foreach($partidos as $partido){ ?>
					<option value="<?php echo $partido['id'] ?>" > <?php echo $partido['nombre'] ?> </option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<!--Fila 2 END-->
		
		
		<!--Fila 3-->
		<tr>
			<td class="regSuaveleft">Departamento: </td>
			<td class="regSuave">
				<div id="combDepartamento" style="display:none;">
					<select name="departamento" onChange="cargarMunicipios(this.value)">
						<option value = "-" >-Ninguna-</option>
						<?php foreach($departamentos as $departamento) { ?>
						<option value="<?php echo $departamento['id'] ?>" > <?php echo $departamento['nombre'] ?> </option>
						<?php } ?>
					</select>
				</div>
			</td>

			<td class="regSuave" colspan="2">
				<div id="divselmunicipio" style="display:none;">
					<select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
						<option value = "-" > Seleccione un departamento </option>
					</select>
				</div>
			</td>
		</tr>
		<!--Fila 3 END-->
		
		<tr>
			<td class="regSuaveRigth" colspan="2">
				<div id="divselzona" style="display:none;"></div>
			</td>
			
			<td class="regSuave" colspan="2">
				<div id="divselcomuna" style="display:none;">
					<select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
						<option value = "-" > Seleccione un municipio </option> 
					</select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="regSuave" colspan="2"> 
				<div id="divselpuesto" style="display:none;">
					<select id="selpuesto" name="puesto" onChange="alert('Cambio el puesto')">
						<option value = "-" > Seleccione un zona o comuna </option> 
					</select>
				</div>
			</td>
			
			<td class="regSuave" colspan="2">
				<div id="divselmesa" style="display:none;">					
					<select id="selmesa" name="mesa" onChange="alert('Cambio la mesa')">
						<option value = "-" > Seleccione un puesto</option> 
					</select>
				</div>
			</td>
		</tr>
		
		
		<tr>
		   <td class="regSuaveRight" colspan="4" >&nbsp;</td>
		</tr>
		
		<tr>
		   <td class="regSuaveRight" colspan="4" >&nbsp;</td>
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
	
	<input type="hidden" name="opcion"  value="1"/>

</form>

<div id="tbConParList"></div>