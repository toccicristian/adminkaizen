<?php
session_start();
include ('../conexion.php');
if (isset($_SESSION['idusuario'])){
    $idUsuario = $_SESSION['idusuario']; // Asegúrate de validar y sanitizar este valor

    $cuentaMensajesNuevos = mysqli_query($conexion, "SELECT COUNT(M.idMensaje) AS cantidad 
        FROM MENSAJES M 
        WHERE M.idReceptor='$idUsuario' AND LEIDO=0");
    
    $resultadoMensajesNuevos = mysqli_fetch_array($cuentaMensajesNuevos);

    header('Content-Type: application/json');
    echo json_encode($resultadoMensajesNuevos);
}

?>