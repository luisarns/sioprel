<html>
<head>
	  <link href="../css/print.css" rel="stylesheet" type="text/css" media="print" /> <!-- siehe screen.css -->
    <link href="../css/screen.css" rel="stylesheet" type="text/css" media="screen, projection" /> 
    <!--[if lte IE 6]><link rel="stylesheet" href="../css/ielte6.css" type="text/css" media="screen" /><![endif]--> 
</head>
<body>
  <div id="container">
  	<div id="main">
        This sample PHP script shows how to use a InterBase database with Server2Go. 
        <br><br>
        <table style="font-size: 9pt;">
        	<tr>
        		<td style="background-color:#C9C8C8">C&oacute;digo</td>
                <td style="background-color:#C9C8C8">Descripci&oacute;n</td>
        	</tr>
			 <?php 
                $host     = $_SERVER["DOCUMENT_ROOT"].'/../dbdir/siprel.gdb';
				$username = 'SYSDBA';
				$password = 'masterkey';
                
                $cnh = ibase_connect($host,$username,$password) or die ("No se pudo conectar la base de datos"); 
                $stament = "SELECT codcorporacion, descripcion FROM pcorporaciones";
				$result = ibase_query($cnh,$stament);
                
				while($row = ibase_fetch_object($result)){
                    echo "<tr>";
                    echo "<td>".$row->CODCORPORACION."</td>";
                    echo "<td>".$row->DESCRIPCION."</td>";
                    echo '</tr>';
				}
				ibase_free_result($result);
				ibase_close($cnh);
             ?> 
   	 </div>
   </div>
</body>
</html>