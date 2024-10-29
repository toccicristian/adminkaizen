<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Alta de tarea</title>
</head>

<body>

<?php


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


	$consulta=mysqli_query($conexion, "INSERT INTO TAREA (Nombre, Inicio, MejorFin, PeorFin, Completada, Notas, OwnerId)
										VALUES('$nombre', '$inicio','$mejorfin','$peorfin',0,'$notas', '$ownerid')");

//select * from TAREA where  idTarea=(select max(idTarea) from TAREA);
	$consulta=mysqli_query($conexion, "SELECT * FROM TAREA WHERE idTarea=(SELECT MAX(idTarea) FROM TAREA)");
	$resultado=mysqli_fetch_array($consulta);
	
	$idtarea=$resultado['idTarea'];

	// echo 'idtarea:'.$idtarea.'<br/>';


	$consulta=mysqli_query($conexion, "INSERT INTO ASIGNACIONDETAREA (USUARIO_IdUsuario, USUARIO_IdRol, TAREA_IdTarea, inicio, mejorFin, peorFin)
										VALUES('$ownerid', '$owneridrol','$idtarea','$inicio','$mejorfin','$peorfin')");

	
	$_SESSION['mensajesistema']="Tarea registrada a nombre de ".$username;


	header("Location:./login.php");
	exit();
	
?>	
    

</body>
</html>