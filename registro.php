<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registro</title>
</head>

<body>

<?php
	$usuario = $_POST['usuario'];
	$clave = md5($_POST['clave']);	
	$email = $_POST['email'];
	$rol = $_POST['nivel'];


	include("conexion.php");

	$consulta=mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE Nombre='$usuario'");
	if(mysqli_num_rows($consulta)==0){
		$consulta=mysqli_query($conexion, "SELECT * FROM ROL 
											WHERE Nombre='$rol'");
		$respuesta=mysqli_fetch_array($consulta);
		$nivel=$respuesta['IdRol'];

		$consulta = mysqli_query($conexion, "INSERT INTO USUARIO (Nombre, Clave, EMail, ROL_IdRol) 
											VALUES('$usuario','$clave','$email','$nivel')");

		$_SESSION['mensajesistema']="Usuario ".$usuario." registrado!";
	}else{
		$_SESSION['mensajesistema']="***El usuario ".$usuario." no se pudo crear porque ya existe.";
	}


	header("Location:./login.php");
	exit();
	
?>	
    

</body>
</html>