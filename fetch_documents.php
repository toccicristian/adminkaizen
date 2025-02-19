<?php session_start();?>

<?php
include('conexion.php');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$idTarea=$_SESSION['taskid'];

$query = "SELECT IdDocumento, Url, Detalle FROM DOCUMENTO WHERE Eliminado = 0 and TAREA_idTarea='$idTarea'";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo '<table>';
        echo '<tr>';
        echo    '<td>';
        echo    ' <button onclick="deleteDocument(' . $row['IdDocumento'] . ')" class="btn btn-danger">X</button>';
        echo    '</td>';                
        echo    '<td>';
        echo     '<a href="' . htmlspecialchars($row['Url']) . '" target="_blank">' . htmlspecialchars($row['Detalle']) . '</a>';
        echo    '</td>';
        echo '</tr>';
        echo '</table>';

    }
} else {
    echo "No hay documentos subidos.";
}

$conexion->close();
?>
