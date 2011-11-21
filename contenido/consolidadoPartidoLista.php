<?php
    include_once('corporaciones.php');
    include_once('partidos.php');
    include_once('departamentos.php');
//    echo $query;
?>

<script type="text/javascript" src="js/consolidadoPartidoLista.js"> </script>

<form name="formPrincipal" method="GET" onSubmit="return validar(this);">

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
                <td class="regOscuro">
                        <strong>&nbsp;</strong>
                </td>
            </tr>
	</table>
	
	<!--Tabla con los campos de filtrado -->
	<table width="100%" cellspacing="3" cellpadding="0" class="regSuaveLeft">
            
            <tr>
                <td>
                    Corporaci&oacute;n&nbsp;
                    <select name="corporacion" onChange="mostrarOcultarDepto(this.value)">
                        <option value = "-" >-Ninguna-</option>
                        <?php foreach($corporaciones as $corporacion) { ?>
                        <option value="<?php echo $corporacion['id'] ?>" > <?php echo $corporacion['nombre'] ?> </option>
                        <?php } ?>
                    </select>
<!--                Detallado&nbsp;<input type="checkbox" value="1" name="detallado"/>-->
                </td>
            </tr>
		
            <!--Fila 2 -->
            <tr>
                <td>
                    Partido&nbsp;
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
                <td id="tdDepto" style="display:none">
                    Departamento&nbsp;
                    <select name="departamento" onChange="cargarMunicipios(this.value)">
                        <option value = "-" >-Ninguna-</option>
                        <?php foreach($departamentos as $departamento) { ?>
                        <option value="<?php echo $departamento['id'] ?>" > <?php echo $departamento['nombre'] ?> </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <!--Fila 3 END-->

            <tr>
                <td id="etfMunicipio" style="display:none">&nbsp;</td>
            </tr>
                
            <tr>
                <td id="etfZonaComuna" style="display:none">&nbsp;</td>
            </tr>
            
            <tr>
                <td id="etfPuesto" style="display:none">&nbsp;</td>    
            </tr>
            
            <tr>
                <td id="etfMesa" style="display:none">&nbsp;</td>
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