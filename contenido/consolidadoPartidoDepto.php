<?php
    /**
    * Seleccionar el departamento ya que hay mas de uno, si fuera a nivel 
    * nacional no tendriamos que dar la opccion de seleccionar nada de la 
    * divipol ya que solo hay una nacion
    */

    /**
    * Consolidado por partido político a nivel departamental. Se debe presentar al frente
    * de cada partido, un comparativo en donde se aprecien el número de
    * candidatos avalados, número de candidatos elegidos y la respectiva votación
    * alcanzada por el partido político. De igual manera, acceder a los respectivos
    * nombres de ciudadanos inscritos y elegidos por el partido político
    * 
    * @author Luis A. Nuñez
    * @since 2011-Nov-03
    */
    include_once('departamentos.php');
    include_once('corporaciones.php');
?>

<script type="text/javascript" src="js/consolidadoPartidoDepto.js"> </script>

<form name="formPrincipal" method="GET" onSubmit="return validar(this);" >

	<!--Tabla imagen titulo superior -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
            <tr>
                <td width="5%" background="../images/ds_comp_bars_gral.jpg" >
                        <img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
                </td>
                <td width="83%" background="../images/ds_comp_bars_gral.jpg" >
                        <strong>Consolidado Partido Departamental</strong>
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
	
	
	<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave" >
		
                <tr>
                    <td class="regSuaveLeft">
                        Corporaci&oacute;n&nbsp;
                        <select name="corporacion" onChange="mostrarOcultarDepto(this.value)" >
                            <option value = "-" >-Ninguna-</option>
                            <?php foreach($corporaciones as $corporacion) { ?>
                            <option value="<?php echo $corporacion['id'] ?>" > <?php echo $corporacion['nombre'] ?> </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            
		<tr>
                    <td class="regSuaveLeft">
                        Departamento&nbsp;
                        <select name="departamento" onChange="cargarMunicipios(this.value)">
                            <option value = "-" >-Ninguna-</option>
                            <?php foreach($departamentos as $departamento) { ?>
                            <option value="<?php echo $departamento['id'] ?>" > <?php echo $departamento['nombre'] ?> </option>
                            <?php } ?>
                        </select>
                    </td>
		</tr>                
                
                <!--Para cuando la corporacion seleccionada es de un nivel inferior al departamento -->
                <tr>
                    <td id="divselmunicipio" style="display:none;">
                        Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
                        <option value = "-" > Seleccione un departamento </option> </select>
                    </td>
                </tr>
                
                
                <tr>
                    <td id="divselcomuna" style="display:none">
                        Comuna : <select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
                        <option value = "-" > Seleccione un municipio </option> </select>
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

</form>

<div id="tbConParDepto"></div>