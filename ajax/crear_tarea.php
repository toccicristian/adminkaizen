<?php 
session_start();
include("../conexion.php");

$errores="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //intento conseguir el IdUsuario:
    $username = $_SESSION['nombre'];

    $query = "SELECT IdUsuario FROM USUARIO WHERE USUARIO.Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

	$arr = $resultado->fetch_array();

    // Verifico si el usuario todavia existe

    if ($resultado->num_rows > 0) {    
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        $arr = $resultado->fetch_assoc();
        $ownerId = $arr['IdUsuario'];
    } else {
        $errores=$errores."Ya no existe el usuario que dió la orden para crear la tarea.\n";
    }




    if (isset($_POST['nombretarea'])){
        $nombre=$_POST['nombretarea'];

        //reviso si ya existe una tarea con ese nombre:
        $query="SELECT Nombre, eliminada FROM TAREA WHERE TAREA.Nombre = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
		$fila = $resultado->fetch_assoc();
        if ($resultado->num_rows != 0) {
            $errores=$errores."Ya existe una tarea con ese nombre.\n";
        }

	} else {
        $errores=$errores."Falta asignar un nombre a la Tarea.\n";
    }
    if (isset($_POST['startdate'])){
		$startDate=$_POST['startdate'];
	} else {
        $errores=$errores."Falta asignar la fecha de inicio.\n";
    }    
    if (isset($_POST['enddate'])){
		$endDate=$_POST['enddate'];
	} else {
        $errores=$errores."Falta asignar la fecha de finalizacion.\n";
    }    
    if ($endDate < $startDate){
        $errores=$errores."La fecha de finalizacion no puede ser anterior a la fecha de inicio.\n";
    }
    if (isset($_POST['notes'])){
		$notes=$_POST['notes'];
	} else {
        $notes="";
    }    

    if(strlen($errores)==0){

        $owneridrol = $_SESSION['idrol'];


        /*FINALMENTE INSERTO LA TAREA*/
        $query = "INSERT INTO TAREA (Nombre, Inicio, MejorFin, PeorFin, Completada, Notas, OwnerId) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);

        if ($stmt) {
            $completada = 0; // Valor fijo para Completada
            $stmt->bind_param("ssssisi", $nombre, $startDate, $startDate, $endDate, $completada, $notes, $ownerId);
            
            if ($stmt->execute()) {
                echo "Tarea creada correctamente por el usuario ".$username.".";
            } else {
                echo "Error al insertar la tarea: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conexion->error;
        }
    
    } else {
        echo $errores;
    }


} else {
    echo "Método de solicitud no válido.";
}
	
?>