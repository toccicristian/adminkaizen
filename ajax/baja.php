<?php
session_start();
include('../conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {

        $idUsuario = $_POST['id'];

        // Verificar si el usuario existe
        $query = "SELECT Nombre FROM USUARIO WHERE IdUsuario=?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Marco el usuario como de baja
            $query = "UPDATE USUARIO SET eliminado=1 WHERE IdUsuario=?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $idUsuario);
            if ($stmt->execute()) {
                echo "El usuario ha sido eliminado.";
            } else {
                echo "No se ha podido eliminar el usuario.";
            }
        } else {
            echo "No se ha podido eliminar el usuario porque no existe.";
        }
        $stmt->close();
    } else {
        echo "No se han recibido los datos necesarios para eliminar.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>
