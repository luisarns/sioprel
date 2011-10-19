<!-- Caso inicial cuando muestro el formulario para hacer los filtros -->
<?php if(!isset($_GET['consultar'])) { ?>
	<?php
		include_once('corporaciones.php');
		include_once('partidos.php');
		include_once('departamentos.php');
	?>

	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/valConParLis.js"> </script>

	<h3>Formulario Consolidado Partido Lista</h3>
	<form name="formPrincipal" method="GET" onSubmit="return validar(this);">
		
		<!--//-->
		Corporaci&oacute;n : <select name="corporacion" onChange="mostrarOcultarDepto(this.value)">
		<option value = "-" > - </option>
		<?php foreach($corporaciones as $corporacion){ ?>
		<option value=<?php echo $corporacion['id'] ?> > <?php echo $corporacion['nombre'] ?> </option>
		<?php } ?>
		</select>
		<!--//-->
		
		<!--//-->
		Detallado : <input type="checkbox" value="1" name="detallado"/>
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
		
		<input type="submit" name="consultar" value="Consultar"/><input type="reset" name="limpiar" value="Limpiar" />
		<!-- Pensar en la posibilidad de una tabla para organizar los campos -->
		<input type="hidden" name="opcion"  value="1"/>
		
	</form>
<?php } else if ( $_GET['consultar'] == "Consultar") { ?>

	<?php
		require('conexion.php');
		include_once('FunDivipol.php');
		
		$codcorporacion = $_GET['corporacion'];
		$nivcorpo  = getNivelCorporacion($codcorporacion);
		
		
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
		
		$codcordiv = substr($coddivipol,0,getNumDigitos($nivcorpo));
		
		$texto1 = (isset($_GET['mesa']) && $_GET['mesa'] != "-")?" AND pm.codtransmision = '".$_GET['mesa']."'  ":"";
		$texto2 = (isset($_GET['comuna']) && $_GET['comuna'] != "-")?" AND pc.idcomuna = ".$_GET['comuna']:"";
		$texto3 = (isset($_GET['partido']) && $_GET['partido'] != "-")?" AND pp.codpartido = ".$_GET['partido']:"";
		
		$query =<<<EOF
		SELECT pp.codpartido as codigo ,pp.descripcion, SUM(mv.numvotos) as votos
		FROM PPARTIDOS pp, PMESAS pm, PCANDIDATOS pc, MVOTOS mv
		WHERE pp.codpartido = pc.codpartido $texto1
		AND pm.codtransmision = mv.codtransmision $texto2
		AND pc.idcandidato = mv.idcandidato $texto3
		AND pc.coddivipol LIKE '$codcordiv'  || '%'
		AND pm.coddivipol LIKE '$coddivipol' || '%'
		AND pc.codnivel = $nivcorpo
		AND pm.codcorporacion = $codcorporacion
		GROUP BY pp.codpartido, pp.descripcion;
EOF;
		
		$firebird = ibase_connect($host,$username,$password) or die("No se pudo conectar a la base de datos: ".ibase_errmsg());
		$result   = ibase_query($firebird,$query);
		
	?>

	<!-- En esta parte incluyo el codigo necesario para mostrar la tabla con los datos de la consulta -->
	<!-- Adicionar los links para hacer la descarga de los correspondientes reportes -->
	<table>
		<tr>
			<th>Codigo</th>
			<th>Nombre</th>
			<th>Votos</th>
		</tr>
		<?php while($row = ibase_fetch_object($result)) { ?>
			<tr>
				<td><?php echo $row->CODIGO?></td>
				<td><?php echo htmlentities($row->DESCRIPCION)?></td>
				<td><?php echo $row->VOTOS?></td>
			</tr>
		<?php } ?>
	</table>

	
	<?php 
		//Cierro la conexion
		ibase_free_result($result);
		ibase_close($firebird);
	?>

<?php } ?>



<!--Mostrar la tabla en el segundo caso cuando se envio la peticion-->
<!-- Aqui abajo va el codigo necesario para contruir la tabla con los datos y los links para hacer la descarga 
	de los reportes
-->
