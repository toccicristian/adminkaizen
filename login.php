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
<title>Admin Kaizen - Login de Usuarios</title>
</head>

<body>

<?php
$usuario=$_POST['usuario'];
$password=$_POST['password'];

include("conexion.php");

$consulta=mysqli_query($conexion, "SELECT USUARIO.Nombre, USUARIO.EMail, ROL.IdRol, ROL.Nombre ROL_Nombre 
									FROM USUARIO 
									INNER JOIN ROL
										ON USUARIO.ROL_IdRol = ROL.IdRol
									WHERE USUARIO.Nombre='$usuario' AND USUARIO.Clave='$password'");
//le puse ROL_Nombre a la columna ROL.Nombre porque la tabla usuario tenia una columna Nombre.

if(mysqli_num_rows($consulta)!=0){
	$respuesta=mysqli_fetch_array($consulta);
	
	$_SESSION['nombre']=$respuesta['Nombre'];
	$_SESSION['email']=$respuesta['EMail'];
	$_SESSION['idrol']=$respuesta['IdRol'];
	$_SESSION['rol']=$respuesta['ROL_Nombre'];
		?>
		<header>

		<?php
		echo "<p>Bienvenid@, ".$_SESSION['nombre']."!</p>";
		echo "Sus Privilegios son : ".$_SESSION['rol']."<br />";
		echo "<h3 class='centrado'>DASHBOARD</h3>";

		?>
		</header>
		<?php

		if($_SESSION['idrol']<mysqli_query($conexion, "SELECT MAX(IdRol) FROM ROL")){
			echo "GESTION DE USUARIOS:<br />";
			?>
			<ul>
				<li>Alta de usuario:
					<form action="registro.php" method="post" >

						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
        				<input type="password" maxlength=12 placeholder="Clave" name="clave" required />
        				<input type="email" maxlength=80 placeholder="Correo" name="email" required />
						<select name="nivel">
                			 <?php 
							 $consulta_niveles=mysqli_query($conexion, "SELECT IdRol, Nombre FROM ROL");
							 $resultado_niveles=mysqli_num_rows($consulta_niveles);
							 while($resultado_niveles=mysqli_fetch_array($consulta_niveles)){
								if ($resultado_niveles['IdRol']>=$_SESSION['idrol']){
									?><option <?php if ($resultado_niveles['IdRol']==5){echo "selected";}?>><?php echo $resultado_niveles['Nombre']?></option><?php
								}
							 }
							?> 
            			</select>
        				<input class="inline-form-button" type="submit" value="Registrar"/>
    				</form>

				</li>
				<li>Baja de Usuario 
					<form action="baja.php" method="post" >
						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
						<input class="inline-form-button" type="submit" value="Dar Baja"/><br />
						<label for="confirmabaja"><input type="checkbox" name="confirmabaja">Confirma Baja</label>
					</form>
				</li>
				<li>Busqueda de Usuarios
					<form action="busqueda.php" method="post" >
						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
						<input class="inline-form-button" type="submit" value="Buscar"/><br />
					</form>
				</li>
			</ul>
			<?php
		}
		// if($_SESSION['idrol']<=2){
		// 	echo "OPCIONES DE SOCIO<br />";
		// }
		// if($_SESSION['idrol']<=3){
		// 	echo "OPCIONES DE GERENTE<br />";
		// }
		// if($_SESSION['idrol']<=4){
		// 	echo "OPCIONES DE SUPERVISOR<br />";
		// }
		// if($_SESSION['idrol']<=5){
		// 	echo "OPCIONES DE OPERARIO<br />";
		// }
		echo "<a href='panel.php'>Panel</a>";	

}else{
	?>
	<p class="error-centrado"><?php echo "No es un usuario registrado"; ?></p>
	<?php
	
	include ("form_registro.php");
}

?>

</body>
</html>