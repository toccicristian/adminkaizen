<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Desasignacion de tarea</title>
</head>

<body>

<?php


	include("conexion.php");

	$taskid=$_POST['taskid'];
	$userid=$_POST['userid'];
	$consulta = mysqli_query($conexion, "DELETE FROM ASIGNACIONDETAREA WHERE ASIGNACIONDETAREA.TAREA_IdTarea=$taskid AND ASIGNACIONDETAREA.USUARIO_IdUsuario=$userid");

	$asignador = $_SESSION['nombre'];
	$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$asignador'");
	$resultado = mysqli_fetch_array($consulta);
	$idasignador= $resultado['IdUsuario'];

	$idasignado=$_POST['userid'];
	$consulta = mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE IdUsuario = '$idasignado'");
	$resultado = mysqli_fetch_array($consulta);
	$asignado=$resultado['Nombre'];


	$_SESSION['mensajesistema']="Tarea de ".$asignado." desasignada por ".$asignador.".";
	$_SESSION['idtask']=$taskid;

	// include("asignaciontarea.php");
	header("Location:./asignaciontarea.php");
	exit();
	
?>	
    

</body>
</html>