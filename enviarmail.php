<?php session_start();?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="css/estilos.css">

<title>Enviando mail...</title>
</head>

<body>
<?php

include('conexion.php');

$nombre=$_SESSION['nombre'];
$email=$_SESSION['email'];
$destino=$_POST['destinatario'];
$asunto=$_POST['asunto'];

$mensaje=$_POST['mensaje'];


$mensaje="Nombre: ".$nombre." Email: ".$email." Mensaje: ".$_POST['mensaje'];
$header="From: ".$nombre."<".$email.">";

$enviado = mail($destino,$asunto,$mensaje,$header);

if($enviado == true){
	echo "Su correo ha sido enviado.";
}else{
	echo "Hubo un error en el envio del mail. Posiblemente el servidor smtp se encuentra offline o no ha sido configurado.";
}



// $consulta=mysqli_query($conexion, "INSERT INTO contactos VALUES ('','$nombre','$email','$localidad','$comentario')");

$idRemitente=$_SESSION['idusuario'];
$consulta=mysqli_query($conexion,"SELECT IdUsuario FROM USUARIO WHERE EMail='$destino'");
$respuesta=mysqli_fetch_array($consulta);
$idReceptor=$respuesta['IdUsuario'];

$consulta = mysqli_query($conexion, "INSERT INTO MENSAJES (idRemitente,idReceptor,asunto,cuerpo)VALUES ( '$idRemitente','$idReceptor','$asunto','$mensaje')") or die(mysqli_error($conexion));

?>
</body>
</html>