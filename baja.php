<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Baja</title>
</head>

<body>

<?php
	$usuario = $_POST['usuario'];

	include("conexion.php");
	if (isset($_POST['confirmabaja'])){
		$consulta=mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE Nombre='$usuario'");
		if(mysqli_num_rows($consulta)!=0){

			mysqli_query($conexion,"DELETE FROM USUARIO WHERE Nombre='$usuario'");
			$_SESSION['mensajesistema']="Usuario ".$usuario." eliminado...";
		}else{
			$_SESSION['mensajesistema']="***El usuario ".$usuario." no se pudo eliminar porque no existe.";
		}
	}
	else{
		$_SESSION['mensajesistema']="***No se ha eliminado a ".$usuario." ya que no ha confirmado la baja.";
	}


	header("Location:./login.php");
	exit();
	
?>	
    

</body>
</html>