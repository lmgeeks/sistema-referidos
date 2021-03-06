<?php
include("../conexion.php");
user_login();
validar_admin();
?>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="referidos v1.0">
    <meta name="author" content="santiago bernal betancourth">
    <link rel="shortcut icon" href="../images/favicon.ico">
    <title><?php 
    if ($_GET['mostrar'] == 'team') {
        echo "view Team";
    }else {
        echo "view Source";
    }

    ?> - referidos v1.0</title>
    <link href="../estilos/bootstrap.min.css" rel="stylesheet">
    <link href="../estilos/signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php mostrar_header();  ?>
<br />
<div class="container">
<?php 
echo "<table class='table table-striped table-bordered' >"; 
echo "<tr>"; 
echo "<td><b>Id</b></td>"; 
echo "<td><b>Fecha registro</b></td>"; 
echo "<td><b>Nick</b></td>";
echo "<td><b>nombre</b></td>";
echo "<td><b>M&oacute;vil</b></td>";    
echo "<td><b>Mail</b></td>";
if($_SESSION['perfil'] == "administrador" && $_GET['mostrar'] == 'vendedores'){ 
echo "<td><b>Team</b></td>";
}   
echo "</tr>"; 
      //codigo para paginacion (no tildes) 
        if (isset($_GET['pagina']) ) { 
    	$num_pagina = (int) $_GET['pagina']; 
    	$num_pagina2 = $num_pagina * 30;
    	} 
    	else {
    	$num_pagina = 0;
    	$num_pagina2 = 0;
    	}
    	// fin de codigo de paginacion (no tildes)
$num_resultados = 0;

if($_SESSION['perfil'] == "administrador"){  
    if ($_GET['mostrar'] == 'team') {
      $result = mysql_query("SELECT * FROM `usuarios` WHERE `tipoperfil` = '3'  LIMIT $num_pagina2 , 30") or trigger_error(mysql_error()); 
    }else {
      $result = mysql_query("SELECT * FROM `usuarios` WHERE `tipoperfil` = '2'  LIMIT $num_pagina2 , 30") or trigger_error(mysql_error()); 
    }
  }else if($_SESSION['perfil'] == "team"){
      $creado_por = $_SESSION['id'];
      $result = mysql_query("SELECT * FROM `usuarios` WHERE `tipoperfil` = '2' AND `creado_por` =  '$creado_por' LIMIT $num_pagina2 , 30") or trigger_error(mysql_error()); 
  }   

while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
$num_resultados = $num_resultados + 1;
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['id']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['fecha']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['nick']) . "</td>";
echo "<td valign='top'>" . nl2br( $row['nombre_completo']) . "</td>";
echo "<td valign='top'>" . nl2br( $row['movil']) . "</td>";   
echo "<td valign='top'>" . nl2br( $row['mail']) . "</td>";  
if($_SESSION['perfil'] == "administrador" && $_GET['mostrar'] == 'vendedores'){ 
 //funcion para obtener el nombre completo del team (persona) que registró a este vendedor
    $creado_por_2 = $row['creado_por']; 
    $result2 = mysql_query("SELECT nombre_completo FROM `usuarios` WHERE `id` = $creado_por_2") or trigger_error(mysql_error());
    while($row2 = mysql_fetch_array($result2)){ 
    foreach($row2 AS $key => $value) { $row2[$key] = stripslashes($value); } 
    echo "<td valign='top'>".nl2br( $row2['nombre_completo'])."</td>";
  }
 //fin función 
}
if($_SESSION['perfil'] == "administrador"){ 	
echo "<td valign='top'><a href=editar.php?id={$row['id']} class='btn btn-warning' role='button'>Editar</a></td><td><a href=borrar.php?id={$row['id']} class='btn btn-danger' role='button'>Borrar</a></td> "; 
}
echo "</tr>"; 
} 
echo "</table>"; 
//codigo para paginacion
	$pag_anterior = $num_pagina - 1;
	$pag_siguiente = $num_pagina + 1;

	echo "<center>";
	if (isset($_GET['pagina']) ) {
	if ($_GET['pagina'] == 0 ) { 
    $pag_anterior = 0;
	}
  else {
   echo  "<a href='index.php?pagina=0' class='btn btn-info' role='button'>P&aacute;gina 1</a> - ";
  echo "<a href='index.php?pagina=$pag_anterior' class='btn btn-info' role='button'>Anterior</a> - ";
   }
  }
  if ($num_resultados > 29) {
     echo "<a href='index.php?pagina=$pag_siguiente' class='btn btn-info' role='button'>Siguiente</a> </center><p>";
    }
  //fin codigo para paginacion
?>
		<?php 
    if ($_GET['mostrar'] == 'team') {
      echo "<a href='agregar.php?add=team' class='btn btn-primary' role='button'>Add Team</a>";
      }else {
        echo "<a href='agregar.php?add=source' class='btn btn-primary' role='button'>Add Source</a>";
      }  
    ?>
	</div> 
</body>
</html>