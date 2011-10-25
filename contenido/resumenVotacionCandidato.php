<?php if(!isset($_GET['consultar'])) { ?>

	<?php
		include_once('corporaciones.php');
		include_once('departamentos.php');
	?>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/resVotCand.js"></script>
	
	<!--<h3>Formulario Resumen Votación Candidato</h3>-->
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="5%" background="../images/ds_comp_bars_gral.jpg">
				<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
				</td>
				<td width="83%" background="../images/ds_comp_bars_gral.jpg">
					<font size="2"><strong>Resumen Votación Candidatos </strong></font>
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
			
			<!--Fila 1 -->
			<tr>
				<td class="regSuaveleft" >Corporaci&oacute;n :</td>
				<td class="regSuaveleft">
					<select name="corporacion" onChange="mostrarDepto(this.value)">
						<option value = "-" > - </option>
						<?php foreach($corporaciones as $corporacion){ ?>
						<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
						<?php } ?>
					</select>
				</td>
				<td class="regSuaveleft" colspan="2">
					<div id="divseldepto" style="display:none;">
					Departamento : <select name="departamento" onChange="cargarMunicipios(this.value)">
					<option value = "-" > - </option>
					<?php foreach($departamentos as $departamento) { ?>
					<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
					<?php } ?>
					</select>
					</div>
				</td>
			</tr>
			
			<!-- Fila 2-->
			<tr>
				<td class="regSuaveRigth" colspan="2">
					<div id="divselmunicipio" style="display:none;">
					Municipio : <select id="selmunicipio" name="municipio" onChange="alert('Cambio el municipio')">
					<option value = "-" > Seleccione un departamento </option> </select>
					</div>
				</td>
				<td class="regSuaveRigth" colspan="2">
					<div id="divselcomuna" style="display:none;">
					Comuna : <select id="selcomuna" name="comuna" onChange="alert('Cambio la comuna')">
					<option value = "-" > Seleccione un municipio </option> </select>
					</div>
				</td>
			</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td align="center" class='regOscuroCenter'>   
					<input type="submit" class="hospital" name="consultar" value="Consultar"/>
					<input type="reset"  class="hospital" name="limpiar" value="Limpiar" />
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

		<input type="hidden" name="opcion"  value="3"/>
	
	</form>
<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php 
		
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repResVotaCandidato.php".$_SERVER['REQUEST_URI'];
		
		$codcorporacion = $_GET['corporacion'];
		$coddepto = $_GET['departamento'];
		$codmunip = $_GET['municipio'];
		
		$txt = "";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$txt = "AND pc.idcomuna = ".$_GET['comuna'];
			$txt .= " AND pd.idcomuna = ".$_GET['comuna'];
		}
		
		$urlReportes .="&formato=";
		
		$codcordivi = $coddepto."".$codmunip;
		$nivcorpo = getNivelCorporacion($codcorporacion);
		$cordivi = substr($codcordivi,0,getNumDigitos($nivcorpo));
		
		$query =<<<EOF
		SELECT pc.codpartido || '-' || pc.codcandidato as codigo  ,pc.nombres, pc.apellidos ,sum(mv.numvotos) as votos
		FROM pmesas pm, mvotos mv, pcandidatos pc, pdivipol pd
		WHERE pd.coddivipol = pm.coddivipol AND pd.codnivel = 4
		AND pd.coddivipol LIKE '$codcordivi' || '%' $txt
		AND pc.coddivipol LIKE '$cordivi' || '%' AND pc.codnivel = $nivcorpo
		AND mv.codtransmision = pm.codtransmision AND pc.codcandidato <> 0
		AND pc.idcandidato = mv.idcandidato AND pc.codcorporacion = $codcorporacion
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pc.codpartido,pc.codcandidato,pc.nombres,pc.apellidos 
		ORDER BY pc.codpartido, pc.codcandidato
EOF;

		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
	?>
	<table>
	<tr>
		<td><h4>Descargar</h4></td>
		<td><a href="<?php echo $urlReportes."pdf"?>" target="_BLANK"><img src="images/logo_pdf.png"  alt="pdf" height="35" width="35" /></a><td>
		<td><a href="<?php echo $urlReportes."xls"?>" target="_BLANK"><img src="images/logo_xls.jpg"  alt="xls" height="35" width="35" /></a><td>
		<td><a href="<?php echo $urlReportes."doc"?>" target="_BLANK"><img src="images/logo_doc.jpg"  alt="doc" height="35" width="35" /></a><td>
		<td><a href="<?php echo $urlReportes."txt"?>" target="_BLANK"><img src="images/logo_text.jpg" alt="txt" height="35" width="35" /></a><td>
	</tr>
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="5%" background="../images/ds_comp_bars_gral.jpg">
			<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
			</td>
			<td width="83%" background="../images/ds_comp_bars_gral.jpg">
				<font size="2"><strong>Resumen Votación Candidatos </strong></font>
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
			<th>Código</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODIGO?></td>
				<td><?php echo htmlentities($row->NOMBRES)?></td>
				<td><?php echo htmlentities($row->APELLIDOS)?></td>
				<td><?php echo number_format($row->VOTOS)?></td>
			</tr>
		<?php } ?>
		
	</table>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td class="regOscuro" align="left">
				<STRONG>&nbsp;</STRONG>
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
	
	<?php 
		ibase_free_result($result);
		ibase_close($firebird);
	?>
	
<?php } ?>
