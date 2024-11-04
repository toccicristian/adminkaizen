<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Restauración de tarea</title>
</head>

<body>

<?php


	include("conexion.php");


	$username = $_SESSION['nombre'];

	$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$username'");
	$resultado = mysqli_fetch_array($consulta);
	$restauradorid= $resultado['IdUsuario'];
	$restauradoridrol = $_SESSION['idrol'];

	$taskid = $_POST['taskid'];

	$consultaExiste=mysqli_query($conexion, "SELECT idTarea, Nombre, OwnerId, eliminada 
											FROM TAREA 
											WHERE TAREA.idTarea = '$taskid' AND TAREA.eliminada = 1");


	if(mysqli_num_rows($consultaExiste)!=0){
		$resultadoExiste = mysqli_fetch_array($consultaExiste);
		$ownerId = $resultadoExiste['OwnerId'];

		$consultaOwner=mysqli_query($conexion, "SELECT ROL_IdRol FROM USUARIO WHERE USUARIO.IdUsuario = '$ownerId'");
		$resultadoOwner=mysqli_fetch_array($consultaOwner);
		$ownerIdRol=$resultadoOwner['ROL_IdRol'];


		if(($restauradorid == $ownerId) || ($restauradoridrol<$ownerIdRol)){
			$idtarea = $resultadoExiste['idTarea'];
			$consulta=mysqli_query($conexion, "UPDATE TAREA 
												SET eliminada=0 
												WHERE TAREA.idTarea = '$idtarea'");
	
			$_SESSION['mensajesistema']="La tarea ha sido restaurada.";	
		}else{
			$_SESSION['mensajesistema']="No dispone de los permisos para restaurar la tarea.";	
		}

	}else{
		$_SESSION['mensajesistema']="La tarea no se ha podido restaurar debido a que no existe o que no se encuentra eliminada.";	
	}

	header("Location:./login.php");
	exit();
	
?>	
    

</body>
</html>