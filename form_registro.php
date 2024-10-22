<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- <link href="https://fonts.googleapis.com/css2?family=Amarante&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="css/estilos.css">
<title>Admin Kaizen - Registro de Usuario</title>
</head>

<body>
	<?php include("conexion.php"); ?>

	<header>
		<h1>Admin Kaizen</h1>
		<nav class="navbar">
			<div class="menu">
				<a href="./form_registro.php" class="menu-item seleccionado">Registrarse</a>
				<a href="./form_login.php" class="menu-item">Iniciar sesión</a>
			</div>
			<div class="isologo-small">
				<a href="./index.php"><img class="main-logo-small" src="./imagenes/logo-200px.png" alt="adminkaizen logo"></img></a>
				<p class="isotipo-index">Admin Kaizen</p>
			</div>
		</nav>
	</header>
	<section>
		<article>
			<h2>Registro de Usuario</h2>
			<form action="registro.php" method="post" >
				<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
        		<input type="password" maxlength=12 placeholder="Clave" name="clave" required />
        		<input type="email" maxlength=80 placeholder="Correo" name="email" required />
				<select name="nivel">
               	 	<?php 
					 	$consulta_niveles=mysqli_query($conexion, "SELECT IdRol, Nombre FROM ROL");
					 	$resultado_niveles=mysqli_num_rows($consulta_niveles);
					 	while($resultado_niveles=mysqli_fetch_array($consulta_niveles)){
							if ($resultado_niveles['IdRol']==2){
								?><option selected>
									<?php echo $resultado_niveles['Nombre']?>
									</option><?php
							}
					 	}
					?> 
           		</select>
        		<input class="inline-form-button" type="submit" value="Registrarse"/>
    		</form>
		</article>
	</section>

</body>
</html>