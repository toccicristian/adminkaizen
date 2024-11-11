<?php session_start();?>

<?php

include('conexion.php');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id = $_POST['id'];
$estado = $_POST['estado'];


mysqli_query($conexion,"UPDATE TAREA SET Completada = $estado WHERE idTarea=$id");
mysqli_close($conexion);
?>
