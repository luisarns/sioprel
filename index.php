<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	
	<!-- Archivos .css -->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/menu.css"  />
	
	<!-- Archivos .js -->
	<script type="text/javascript" src="js/main.js" ></script>
        <script type="text/javascript" src="js/jquery-1.7.min.js"> </script>
	
	<title>Estad&iacute;sticas Electorales</title>
</head>

<body>

<div id="headerbg">
  <div id="headerblank">
    <div id="header">
        <div class="sio"><img src="/images/logosio.jpg" width="200" height="100" alt="Soluciones Integrales De Oficinas" /></div>
        <h1 style="color:#DFDFDF;">
		Estad&iacute;sticas Electorales<br/>
		Escrutinio 2011
        </h1>
    </div>
  </div>
</div>


<div id="contentbg">
  <div id="contentblank">
    <div id="content">
     
	 <div id="contentleft">
		<div id="menu22">
          <ul>
            <li><a href="/?opcion=1">Consolidado Partido Lista </a></li>
            <li><a href="/?opcion=2">Consolidado Partido Depto. </a></li>
            <li><a href="/?opcion=3">Resumen Votaci&oacute;n Candidatos</a></li>
            <li><a href="/?opcion=4">Resumen Votaci&oacute;n Partido </a></li>
            <li><a href="/?opcion=5">Elegidos Corporaciones </a></li>
            <li><a href="/?opcion=6">Listado Votaci&oacute;n Candidatos</a></li>
            <li><a href="/?opcion=7">Resumen Curules Asignadas</a></li>
            <li><a href="/?opcion=8">Listas Mayor Votaci&oacute;n</a></li>
            <li><a href="/?opcion=9">Elegidos Asignaci&oacute;n Curules</a></li>
            <li><a href="ManualEstadisticas/index.html" target="_blank">Manual</a></li>
          </ul>
	  </div>
     </div>
	  
      <div id="contentmid">
            <?php
                switch ($_GET['opcion']) {

                    case 1:
                            require("contenido/consolidadoPartidoLista.php");
                    break;

                    case 2:
                            require("contenido/consolidadoPartidoDepto.php");
                    break;

                    case 3:
                            require("contenido/resumenVotacionCandidato.php");
                    break;

                    case 4:
                            require("contenido/resumenVotacionPartido.php");
                    break;

                    case 5:
                            require("contenido/listadoElegidos.php");
                    break;

                    case 6:
                            require("contenido/listadoVotacionCandidato.php");
                    break;

                    case 7:
                            require("contenido/resumenCurulesAsignadas.php");
                    break;

                    case 8:
                            require("contenido/listasMayorVotacion.php");
                    break;

                    case 9:
                            require("contenido/elegidosAsignCurules.php");
                    break;

                    default:					
                }
            ?>
      </div>

    </div>
  </div>
</div>

<!--Pie de la pagina -->
<div id="footerbg">
  <div id="footerblank">
    <div id="footer">
      <div id="copyrights">
	  SIO WEB SOLUTIONS © Copyright 2011<br/>
	  <!-- <div id="#designedby"><a class="designedby" href="#">Luis A. Nuñez <br/>lnunez.system@gmail.com</a></div>-->
      </div>
    </div>
  </div>
</div>

</body>

</html>