<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<title>Estad&iacute;sticas Electorales</title>
</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
      <h1>Estad&iacute;sticas Electorales</h1>
	</div>
  </div>
</div>

<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentleft">
        <div id="leftnavheading">
          <h4>Menu principal</h4>
        </div>
        <div id="leftnav">
          <ul>
            <li><a href="/?opcion=1" class="leftnav">Consolidado Partido Lista </a></li>
            <li><a href="/?opcion=2" class="leftnav">Consolidado Partido Departamental </a></li>
            <li><a href="/?opcion=3" class="leftnav">Resumen Votaci&oacute;n Candidatos</a></li>
            <li><a href="#" class="leftnav">Resumen Votaci&oacute;n Partido </a></li>
            <li><a href="#" class="leftnav">Elegidos Corporaciones </a></li>
            <li><a href="#" class="leftnav">Listado Votaci&oacute;n Candidatos</a></li>
            <li><a href="#" class="leftnav">Resumen Curules Asignadas</a></li>
            <li><a href="#" class="leftnav">Listas Mayor Votaci&oacute;n</a></li>
            <li><a href="#" class="leftnav">Sollicitudin viverra. </a></li>
            <li><a href="#" class="leftnav">Elegidos Asignaci&oacute;n Curules</a></li>
          </ul>
        </div>
      </div>
	  
      <div id="contentmid">
		<!-- Codigo dinamico que me permite cumplir con los requerimientos -->
		<?php
			switch($_GET['opcion']){
				
				case 1:
					require("contenido/consolidadoPartidoLista.php");
				break;
				case 2:
					require("contenido/consolidadoPartidoDepto.php");
				break;
				case 3:
					require("contenido/resumenVotacionCandidato.php");
				break;
				default:
					echo "HOME PRINCIPAL";//Un require para el home
			}
		?>
      </div>

    </div>
  </div>
</div>

<div id="footerbg">
  <div id="footerblank">
    <div id="footer">
      <div id="copyrights">© Copyright Information Goes Here. All Rights Reserved.</div>
    </div>
  </div>
</div>

</body>

</html>
