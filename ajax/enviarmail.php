<?php
session_start();
include('../conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$asunto='<sin asunto>';
	if (isset($_POST['asunto'])){
		$asunto=$_POST['asunto'];
	}
	$mensaje='<sin asunto>';
	if (isset($_POST['mensaje'])){
		$mensaje=$_POST['mensaje'];
	}
    if (isset($_POST['destinatario'])) {
		$destinatario=$_POST['destinatario'];

        $query = "SELECT IdUsuario FROM USUARIO WHERE EMail=?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $destinatario);
        $stmt->execute();
        $resultado = $stmt->get_result();
		$fila = $resultado->fetch_assoc();

        if ($resultado->num_rows != 0) {
            $idReceptor=$fila['IdUsuario'];
			$idRemitente=$_SESSION['idusuario'];


			$query = "INSERT INTO MENSAJES (idRemitente,idReceptor,asunto,cuerpo) VALUES (?, ?, ?, ?)";
			$stmt = $conexion->prepare($query);
            $stmt->bind_param("iiss", $idRemitente, $idReceptor, $asunto, $mensaje);
            if ($stmt->execute()) {
				$r="El envio del mensaje se ha registrado correctamente";

				$nombre=$_SESSION['nombre'];
				$email=$_SESSION['email'];
				$cuerpoMensaje="Nombre: ".$nombre." Email: ".$email." Mensaje: ".$mensaje;
				$header="From: ".$nombre."<".$email.">";
				$enviado = mail($destinatario,$asunto,$cuerpoMensaje,$header);

				if($enviado == true){
					$r=$r." y se ha enviado el email";
				}else{
					$r=$r.", pero no se ha logrado enviar el email";
				}
				$r=$r.".";

                echo $r;
            } else {
                echo "No se ha podido registrar el envio del mensaje ni enviar el email.";
            }			
        } else {
            echo "No se ha podido enviar el mensaje porque el correo no existe.";
        }
        $stmt->close();
    } else {
        echo "Falta ingresar el destinatario.";
    }
} else {
    echo "Método de solicitud no válido.";
}

$conexion->close();
?>

