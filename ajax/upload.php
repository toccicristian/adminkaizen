<?php session_start();?>

<?php

include('../conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadDir = '../uploads/'; // aca voy a guardar los archivos
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        $detalle = $_POST['detalle'];
        $tarea_id = $_POST['tarea_id'];

        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); //permiso estilo linux, el cero antepuesto marca octal
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {// move_upload_file(archivo origen,archivo destino)
            $stmt = $conexion->prepare("INSERT INTO DOCUMENTO (Url, Detalle, TAREA_idTarea) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $uploadFile, $detalle, $tarea_id);
            if ($stmt->execute()) {
                echo "El documento se ha subido correctamente.";
            } else {
                echo "Error al registrar el documento en la base de datos.";
            }
            $stmt->close();
        } else {
            echo "Error al mover el archivo subido.";
        }
    } else {
        echo "Error en la subida del archivo.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>
