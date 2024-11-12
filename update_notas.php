<?php
session_start();
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tasknotes']) && isset($_POST['taskid'])) {
        $tasknotes = $_POST['tasknotes'];
        $taskid = $_POST['taskid'];

        // Actualizar las notas en la base de datos
        $stmt = $conexion->prepare("UPDATE TAREA SET Notas = ? WHERE idTarea = ?");
        $stmt->bind_param("si", $tasknotes, $taskid);

        if ($stmt->execute()) {
            echo "Las notas se han actualizado correctamente.";
        } else {
            echo "Error al actualizar las notas.";
        }

        $stmt->close();
    } else {
        echo "Datos no válidos.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>
