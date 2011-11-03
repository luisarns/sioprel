<?php
    /*
      * Seleccionar el departamento ya que hay mas de uno, si fuera a nivel 
      * nacional no tendriamos que dar la opccion de seleccionar nada de la 
      * divipol ya que solo hay una nacion
      */

    /**
     * Consolidado por partido pol�tico a nivel departamental. Se debe presentar al frente
     * de cada partido, un comparativo en donde se aprecien el n�mero de
     * candidatos avalados, n�mero de candidatos elegidos y la respectiva votaci�n
     * alcanzada por el partido pol�tico. De igual manera, acceder a los respectivos
     * nombres de ciudadanos inscritos y elegidos por el partido pol�tico
     * 
     * @author Luis A. Nu�ez
     * @since 2011-Nov-03
     */
    require_once 'contenido/departamentos.php';
      
?>

<script type="text/javascript" src="js/valConParDepto.js"> </script>

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
			<td class="regSuaveRight" >Departamento :</td>
			<td class="regSuave" >
				<select name="departamento">
					<option value = "-" >-Ninguna-</option>
					<?php foreach($departamentos as $departamento) { ?>
					<option value="<?php echo $departamento['coddivipol'] ?>" > <?php echo $departamento['nombre'] ?> </option>
					<?php } ?>
				</select>
			</td>
		</tr>
		
		<tr>
		   <td class="regSuaveRight" colspan="2" >&nbsp;</td>
		</tr>
		
		<tr>
		   <td class="regSuaveRight" colspan="2" >&nbsp;</td>
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