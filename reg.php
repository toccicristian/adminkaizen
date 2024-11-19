<?php
session_start();
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['clave']) && isset($_POST['email']) && isset($_POST['nivel'])) {

        $usuario = $_POST['usuario'];
        $clave = md5($_POST['clave']);	
        $email = $_POST['email'];
        $rol = $_POST['nivel'];

        // Verificar si el usuario ya existe
        $query = "SELECT Nombre FROM USUARIO WHERE Nombre=?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 0) {
            // Obtener el ID del rol
            $query = "SELECT IdRol FROM ROL WHERE Nombre=?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("s", $rol);
            $stmt->execute();
            $resultadoNivel = $stmt->get_result();
            $nivelRow = $resultadoNivel->fetch_assoc();
            $nivel = $nivelRow['IdRol'];

            // Insertar el nuevo usuario
            $query = "INSERT INTO USUARIO (Nombre, Clave, EMail, ROL_IdRol) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("sssi", $usuario, $clave, $email, $nivel);
            if ($stmt->execute()) {
                echo "El usuario ha sido registrado correctamente.";
            } else {
                echo "No se ha podido registrar el usuario.";
            }
        } else {
            echo "No se ha podido registrar el usuario porque ya existe.";
        }
        $stmt->close();
    } else {
        echo "Falta ingresar al menos un dato.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>
