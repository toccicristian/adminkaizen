<?php session_start();?>

<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $conexion->prepare("UPDATE DOCUMENTO SET Eliminado = 1 WHERE IdDocumento = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "Documento eliminado correctamente.";
        } else {
            echo "Error al eliminar el documento.";
        }
        $stmt->close();
    } else {
        echo "ID de documento no proporcionado.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>
