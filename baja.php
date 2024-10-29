<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Baja</title>
</head>

<body>

<?php

	include("conexion.php");

	$usuario = $_POST['usuario'];
	$consulta_usuarioidrol = mysqli_query($conexion, "SELECT ROL_IdRol FROM USUARIO WHERE Nombre='$usuario'");
	$respuesta_idrol = mysqli_fetch_array($consulta_usuarioidrol);


	if (isset($_POST['confirmabaja'])){
		if (($_SESSION['idrol'] == 1 || $respuesta_idrol['ROL_IdRol']>$_SESSION['idrol']) && ($usuario!="admin")){
			
			$consulta=mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE Nombre='$usuario'");
			if(mysqli_num_rows($consulta)!=0){
	
				mysqli_query($conexion,"DELETE FROM USUARIO WHERE Nombre='$usuario'");
				$_SESSION['mensajesistema']="Usuario ".$usuario." eliminado...";
			}else{
				$_SESSION['mensajesistema']="***El usuario ".$usuario." no se pudo eliminar porque no existe.";
			}
		}
		else{
			$_SESSION['mensajesistema']="***No posee los permisos suficientes para eliminar a ".$usuario.".";
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