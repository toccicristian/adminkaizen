<?php session_start();
include("conexion.php");
	$username = $_SESSION['nombre'];

	$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$username'");
	$resultado = mysqli_fetch_array($consulta);
	$eliminadorid= $resultado['IdUsuario'];
	$eliminadoridrol = $_SESSION['idrol'];

	$taskid = $_POST['taskid'];

	$consultaExiste=mysqli_query($conexion, "SELECT idTarea, Nombre, OwnerId, eliminada 
											FROM TAREA 
											WHERE TAREA.idTarea = '$taskid' AND TAREA.eliminada = 0");


	if(mysqli_num_rows($consultaExiste)!=0){
		$resultadoExiste = mysqli_fetch_array($consultaExiste);
		$ownerId = $resultadoExiste['OwnerId'];

		$consultaOwner=mysqli_query($conexion, "SELECT ROL_IdRol FROM USUARIO WHERE USUARIO.IdUsuario = '$ownerId'");
		$resultadoOwner=mysqli_fetch_array($consultaOwner);
		$ownerIdRol=$resultadoOwner['ROL_IdRol'];


		if(($eliminadorid == $ownerId) || ($eliminadoridrol<$ownerIdRol)){
			$idtarea = $resultadoExiste['idTarea'];
			$consulta=mysqli_query($conexion, "UPDATE TAREA 
												SET eliminada=1, idEliminador='$eliminadorid' 
												WHERE TAREA.idTarea = '$idtarea'");
	
			$_SESSION['mensajesistema']="La tarea se ha marcado como eliminada.";	
		}else{
			$_SESSION['mensajesistema']="No dispone de los permisos para eliminar la tarea.";	
		}

	}else{
		$_SESSION['mensajesistema']="La tarea no se ha podido eliminar debido a que no existe o que ya habia sido eliminada.";	
	}
	header("Location:./login.php");
	exit();
?>