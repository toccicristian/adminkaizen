<?php 
session_start();
include("conexion.php");
	$username = $_SESSION['nombre'];
	$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$username'");
	$resultado = mysqli_fetch_array($consulta);
	$ownerid= $resultado['IdUsuario'];
	$owneridrol = $_SESSION['idrol'];
	$nombre = $_POST['taskname'];
	$inicio = $_POST['taskstart'];
	$mejorfin = $_POST['taskbestend'];
	$peorfin = $_POST['taskworstend'];
	$notas = $_POST['tasknotes'];

	$consultaExiste=mysqli_query($conexion, "SELECT Nombre, eliminada FROM TAREA WHERE TAREA.Nombre = '$nombre'");
	if(mysqli_num_rows($consultaExiste)!=0){
		$_SESSION['mensajesistema']="***Ya existe una tarea con ese nombre";	
		$resultadoExiste = mysqli_fetch_array($consultaExiste);
		if($resultadoExiste['eliminada']==1){
			$_SESSION['mensajesistema']=$_SESSION['mensajesistema']." y se encuentra eliminada. Intente restaurarla";
		}
		$_SESSION['mensajesistema']=$_SESSION['mensajesistema'].".";
	}else{
		$consulta=mysqli_query($conexion, "INSERT INTO TAREA (Nombre, Inicio, MejorFin, PeorFin, Completada, Notas, OwnerId)
											VALUES('$nombre', '$inicio','$mejorfin','$peorfin',0,'$notas', '$ownerid')");

		$consulta=mysqli_query($conexion, "SELECT * FROM TAREA WHERE idTarea=(SELECT MAX(idTarea) FROM TAREA)");
		$resultado=mysqli_fetch_array($consulta);
		$idtarea=$resultado['idTarea'];

		$consulta=mysqli_query($conexion, "INSERT INTO ASIGNACIONDETAREA (USUARIO_IdUsuario, TAREA_IdTarea, idAsignador)
				VALUES('$ownerid','$idtarea','$ownerid')");

		$_SESSION['mensajesistema']="Tarea registrada a nombre de ".$username;

	}

	header("Location:./login.php");
	exit();
	
?>