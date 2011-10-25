<?php if(!isset($_GET['consultar'])) { ?>
	
	<?php
		include_once('corporaciones.php');
		include_once('partidos.php');
		include_once('departamentos.php');
	?>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/valConParLis.js"> </script>


	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
	
		<!--Tabla imagen titulo superior -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="5%" background="../images/ds_comp_bars_gral.jpg">
					<img src="../images/ds_comp_izq_bar_gral.jpg" width="25" height="25" />
				</td>
				<td width="83%" background="../images/ds_comp_bars_gral.jpg">
					<font size="2"><strong>Consolidado Partido Lista </strong></font>
				</td>
				<td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
					<img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
				</td>
			</tr>
		</table>
		
		<!--Tabla imagen gris superior -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td class="regOscuro" align="left">
					<STRONG>&nbsp;</STRONG>
				</td>
			</tr>
		</table>
		
		
		<!--Tabla con los campos de filtrado -->
		<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave">
			
			<!--Fila 1 -->
			<tr>
				<td class="regSuaveleft" >Corporaci&oacute;n :</td>
				<td class="regSuave">
					<select name="corporacion" onChange="mostrarOcultarDepto(this.value)">
						<option value = "-" > - </option>
						<?php foreach($corporaciones as $corporacion){ ?>
						<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
						<?php } ?>
					</select>
				</td>
				<td class="regSuaveleft" colspan="2">Detallado : <input type="checkbox" value="1" name="detallado"/></td>
			</tr>
			<!--Fila 1 END-->
			
			<!--Fila 2 -->
			<tr>
				<td class="regSuaveleft" width="20%">Partido :</td>
				<td class="regSuave" colspan="3" width="75%">
					<select name="partido">
						<option value = "-" > - </option>
						<?php foreach($partidos as $partido){ ?>
						<option value=<?php echo $partido['id'] ?> > <?php echo $partido['nombre'] ?> </option>
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
							<option value = "-" > - </option>
							<?php foreach($departamentos as $departamento) { ?>
							<option value=<?php echo $departamento['id'] ?> > <?php echo $departamento['nombre'] ?> </option>
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
					<input type="reset" class="hospital" name="limpiar" value="Limpiar" />
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
<?php } else if ( $_GET['consultar'] == "Consultar") { ?>

	<?php
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$urlReportes = "http://".$_SERVER['HTTP_HOST']."/reportes/repConPartidolista.php?";
		
		$codcorporacion = $_GET['corporacion'];
		$nivcorpo  = getNivelCorporacion($codcorporacion);
		
		$urlReportes.="codcorporacion=$codcorporacion";
		
		//Codigo para generar el coddivipol
		$coddivipol = $_GET['departamento'];
		$codnivel   = 1;
		
		
		if(isset($_GET['municipio']) && $_GET['municipio'] != "-" ){
			$coddivipol .= $_GET['municipio'];
			$codnivel = 2;
			
			if(isset($_GET['zona']) && $_GET['zona'] != "-" ){
				$coddivipol .= $_GET['zona'];
				$codnivel = 3;
			}
			
			if(isset($_GET['puesto']) && $_GET['puesto'] !="-"){
				$coddivipol = $_GET['puesto'];
				$codnivel = 4;
			}
		}
		
		$urlReportes.="&nivcorpo=$nivcorpo&coddivipol=$coddivipol&codnivel=$codnivel&opcion=1";
		
		$codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));
		
		$texto1 = " ";
		if(isset($_GET['mesa']) && $_GET['mesa'] != "-"){
			$texto1 = " AND pm.codtransmision = '".$_GET['mesa']."'";
			$urlReportes.="&codtransmision=".$_GET['mesa'];
		}
		
		$texto2 ="";
		if(isset($_GET['comuna']) && $_GET['comuna'] != "-"){
			$texto2 = " AND pc.idcomuna = ".$_GET['comuna'];
			$texto2 .= " AND pd.idcomuna = ".$_GET['comuna'];
			$urlReportes.="&idcomuna=".$_GET['comuna'];
		}
		
		$texto3 = "";
		$txt4 = "";
		if(isset($_GET['partido']) && $_GET['partido'] != "-"){
			$texto3 = " AND pp.codpartido = ".$_GET['partido'];
			$txt4 = "AND pc.codpartido = ".$_GET['partido'];
			$urlReportes.="&codpartido=".$_GET['partido'];
		}
		
		//Esta consulta no esta completa, hay que actualizarla
		$query =<<<EOF
		SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
		FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv, pdivipol pd
		WHERE pp.codpartido = pc.codpartido $texto1
		AND pd.coddivipol LIKE '$coddivipol' || '%' AND pd.codnivel = 4
		AND pm.coddivipol = pd.coddivipol
		AND pm.codtransmision = mv.codtransmision $texto2
		AND pc.idcandidato = mv.idcandidato $texto3
		AND pc.coddivipol LIKE '$codcordiv'  || '%'
		AND pc.codnivel = $nivcorpo
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pp.codpartido, pp.descripcion
EOF;
		
		// echo $query."<br/>";
		
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
		$result1 = null;
		$query1 = null;
		if(isset($_GET['detallado']) && $_GET['detallado'] == 1) {
			$query1 =<<<EOR
			SELECT pc.codpartido,pc.codcandidato, pc.nombres ||' '|| CASE WHEN pc.codcandidato = 0 THEN '(LISTA)' ELSE pc.apellidos END as descripcion, SUM(mv.numvotos) as votos
			FROM PMESAS pm, PCANDIDATOS pc, MVOTOS mv
			WHERE pm.codtransmision = mv.codtransmision $texto1
			AND pc.idcandidato = mv.idcandidato $texto2
			AND pc.coddivipol LIKE '$codcordiv' || '%'
			AND pm.coddivipol LIKE '$coddivipol'  || '%'
			AND pm.codcorporacion = $codcorporacion $txt4
			AND pc.codnivel = $nivcorpo
			GROUP BY pc.codpartido,pc.codcandidato,descripcion;
EOR;
			$result1 = ibase_query($firebird,$query1);
			$urlReportes.="&detallado=".$_GET['detallado'];
		}
			
		$urlReportes.="&formato=";
		
		$candidatos = array();
		if($result1 != null){
			while($row = ibase_fetch_object($result1)) {
				array_push($candidatos,$row);
			}
		}
		
	?>

	<!-- En esta parte incluyo el codigo necesario para mostrar la tabla con los datos de la consulta -->
	<!-- Adicionar los links para hacer la descarga de los correspondientes reportes -->
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
				<font size="2"><strong>Consolidado Partido Lista </strong></font>
			</td>
			<td width="12%" align="right" background="../images/ds_comp_bars_gral.jpg">
				<img src="../images/ds_comp_der_bar_gral.jpg" width="25" height="25" />
			</td>
		</tr>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr><td class="regOscuro" align="left"><STRONG>&nbsp;</STRONG></td></tr>
	</table>
	<table width="100%" align="center" border="0" cellspacing="3" cellpadding="0" class="regSuave">
		<tr>
			<th>C&oacute;digo</th>
			<th>Nombre</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODIGO?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
				<td><?php echo number_format($row->VOTOS)?></td>
				
			</tr>
			<?php 
				foreach($candidatos as $candidato) { 
					if($candidato->CODPARTIDO == $row->CODIGO) { ?>
						<tr>
						<td><?php echo $row->CODIGO .'-'.$candidato->CODCANDIDATO ?></td>
						<td><?php echo htmlentities($candidato->DESCRIPCION)?></td>
						<td><?php echo number_format($candidato->VOTOS)?></td>
						</tr>
			<?php }} ?>
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
		if($result1 != null){ibase_free_result($result1);}
		ibase_close($firebird);
	?>

<?php } ?>
