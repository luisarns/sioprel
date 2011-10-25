<?php if(!isset($_GET['consultar'])) { ?>

	<?php
		include_once('corporaciones.php');
		include_once('partidos.php');
		include_once('departamentos.php');
	?>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/valLisEleCorp.js"></script>
	
	<!--<h3>Formulario Listado Elegidos Corporación</h3>-->
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="5%" background="../images/ds_comp_bars_gral.jpg">
			<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
			</td>
			<td width="83%" background="../images/ds_comp_bars_gral.jpg">
				<font size="2"><strong>Formulario Elegidos Corporación</strong></font>
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
					<option value = "-" > - </option>
					<?php foreach($corporaciones as $corporacion){ ?>
					<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
					<?php } ?>
					</select>
				</td>
				
				<td class="regSuaveleft">Sexo :</td>
				<td class="regSuaveleft">
					<select name="sexo" >
						<option value = "-" > - </option>
						<option value = "M" > Masculino </option>
						<option value = "F" > Femenino </option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="regSuaveleft" >Partido :</td>
				<td class="regSuaveleft" colspan="3">
					<select name="partido" >
						<option value = "-" > - </option>
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
					<option value = "-" > - </option>
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
					<!--//-->
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
		
		<input type="hidden" name="opcion"  value="5"/>
	
	</form>

<?php } else if ( $_GET['consultar'] == "Consultar") { ?>
	
	<?php 
		
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repLisElegCorp.php".$_SERVER['REQUEST_URI'];
		
		$codcorporacion = $_GET['corporacion'];
		$coddivipol = $_GET['departamento'];
		$codnivel   = 1;
		
		if(isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
			$coddivipol .= $_GET['municipio'];
			$codnivel = 2;
		}
		
		
		$tx1 = "";
		if($_GET['sexo'] != "-") {
			$tx1 = "AND pc.genero='".$_GET['sexo']."'";
		}
		
		$tx2 = "";
		if($_GET['partido'] != "-") {
			$tx2 = "AND pc.codpartido=".$_GET['partido'];
		}
		
		$tx3 = "";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$tx3 = "AND pc.idcomuna = ".$_GET['comuna'];
		}
		
		$urlReportes .="&formato=";
		
		$nivcorpo = getNivelCorporacion($codcorporacion);
		$cordivi = substr($coddivipol,0,getNumDigitos($nivcorpo));
		
		$query =<<<EOF
		SELECT pc.nombres,pc.apellidos, pp.descripcion, pe.numvotos
		FROM PCANDIDATOS pc, PELEGIDOS pe, PPARTIDOS pp
		WHERE pc.idcandidato = pe.idcandidato $tx1
		AND pc.coddivipol LIKE '$cordivi' || '%' $tx2
		AND pc.codnivel = $nivcorpo $tx3
		AND pp.codpartido = pc.codpartido
		AND pc.codcorporacion = $codcorporacion
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
				<font size="2"><strong>Listado Elegidos Corporación </strong></font>
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
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Partido</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo htmlentities($row->NOMBRES)?></td>
				<td><?php echo htmlentities($row->APELLIDOS)?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
				<td><?php echo number_format($row->NUMVOTOS)?></td>
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
