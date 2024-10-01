<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- <link href="https://fonts.googleapis.com/css2?family=Amarante&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="css/estilos.css">
<title>Admin Kaizen - Login de Usuarios</title>
</head>

<body>

<?php
$usuario=$_POST['usuario'];
$password=$_POST['password'];

include("conexion.php");

$consulta=mysqli_query($conexion, "SELECT Nombres, Apellidos, Correo FROM usuarios WHERE NombreUsuario='$usuario' AND Contraseña='$password'");

$resultado=mysqli_num_rows($consulta);

if($resultado!=0){
	$respuesta=mysqli_fetch_array($consulta);
	
	$_SESSION['nombre']=$respuesta['nombre'];
	$_SESSION['apellido']=$respuesta['apellido'];
		
		echo "Hola ".$_SESSION['nombre']." ".$_SESSION['apellido']."<br />";
		echo "Acceso al panel de usuarios.<br/>";
		echo "<a href='panel.php'>Panel</a>";	

}else{
	?>
	<p class="error-centrado"><?php echo "No es un usuario registrado"; ?></p>
	<?php
	
	include ("form_registro.php");
}

?>

</body>
</html>