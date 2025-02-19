<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Asignacion de tarea</title>
</head>

<body>

<?php


	include("conexion.php");

	$asignador = $_SESSION['nombre'];
	$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$asignador'");
	$resultado = mysqli_fetch_array($consulta);
	$idasignador= $resultado['IdUsuario'];

	$idasignado=$_POST['userid'];
	$consulta = mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE IdUsuario = '$idasignado'");
	$resultado = mysqli_fetch_array($consulta);
	$asignado=$resultado['Nombre'];

	$idtarea=$_POST['taskid'];
	$consultaTarea=mysqli_query($conexion, "SELECT * FROM TAREA WHERE idTarea=$idtarea");
	$resultadoTarea=mysqli_fetch_array($consultaTarea);
	$inicio=$resultadoTarea['Inicio'];
	$mejorfin=$resultadoTarea['MejorFin'];
	$peorfin=$resultadoTarea['PeorFin'];

	$consulta=mysqli_query($conexion, "INSERT INTO ASIGNACIONDETAREA (USUARIO_IdUsuario, TAREA_IdTarea, idAsignador)
			VALUES('$idasignado','$idtarea','$idasignador')");

	$_SESSION['mensajesistema']="Tarea asignada por ".$asignador." a ".$asignado.".";
	$_SESSION['idtask']=$idtarea;

	header("Location:./asignaciontarea.php");
	exit();
	
?>	
    

</body>
</html>