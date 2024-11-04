<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="./js/busqueda.js"></script>

<!-- <link href="https://fonts.googleapis.com/css2?family=Amarante&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">
<title>Admin Kaizen - Login de Usuarios</title>
</head>

<body>

<?php

if ((!isset($_POST['usuario']) || !isset($_POST['password'])) && (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])))
{
	header("Location:./index.php");
	exit();
}

if(isset($_POST['usuario'])&&isset($_POST['password'])){
	$usuario = $_POST['usuario'];
	$password = md5($_POST['password']);
}elseif((isset($_SESSION['usuario'])&&isset($_SESSION['password']))){
	$usuario = $_SESSION['usuario'];
	$password = md5($_SESSION['password']);
}


include("conexion.php");

$consulta=mysqli_query($conexion, "SELECT USUARIO.Nombre, USUARIO.EMail, ROL.IdRol, ROL.Nombre ROL_Nombre 
									FROM USUARIO 
									INNER JOIN ROL
										ON USUARIO.ROL_IdRol = ROL.IdRol
									WHERE USUARIO.Nombre='$usuario' AND USUARIO.Clave='$password'");
//le puse ROL_Nombre a la columna ROL.Nombre porque la tabla usuario tenia una columna Nombre.

if(mysqli_num_rows($consulta)!=0){
	$respuesta=mysqli_fetch_array($consulta);
	
	if(!isset($_SESSION['usuario']) && !isset($_SESSION['password'])){
		$_SESSION['usuario']=$respuesta['Nombre'];
		$_SESSION['password']=$_POST['password'];
	}

	$_SESSION['nombre']=$respuesta['Nombre'];
	$_SESSION['email']=$respuesta['EMail'];
	$_SESSION['idrol']=$respuesta['IdRol'];
	$_SESSION['rol']=$respuesta['ROL_Nombre'];
		?>
		<header>

		<?php
		echo "<p>Bienvenid@, ".$_SESSION['nombre']."!.&nbsp Sus Permisos son de : ".$_SESSION['rol'].".</p>";
		echo "<h3 class='centrado sombreado'>MENU PRINCIPAL</h3>";

		?>
		</header>
		<?php

		if(isset($_SESSION['mensajesistema'])){
			echo "<p class='mensaje-sistema'>".$_SESSION['mensajesistema']."</p>";
			unset($_SESSION['mensajesistema']);
		}

		$consulta_idrol_max=mysqli_query($conexion, "SELECT MAX(IdRol) AS 'max_idrol' FROM ROL");
		$idrol_max=mysqli_fetch_array($consulta_idrol_max);

		if((int)$_SESSION['idrol']<(int)$idrol_max['max_idrol']){
			echo "<h3>GESTION DE USUARIOS:</h3><br />";
			?>
			<ul>
				<li>Alta de usuario:
					<form action="registro.php" method="post" >

						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
        				<input type="password" maxlength=12 placeholder="Clave" name="clave" required />
        				<input type="email" maxlength=80 placeholder="Correo" name="email" required />
						<select name="nivel">
                			 <?php 
							 $consulta_niveles=mysqli_query($conexion, "SELECT IdRol, Nombre FROM ROL");
							 $resultado_niveles=mysqli_num_rows($consulta_niveles);
							 while($resultado_niveles=mysqli_fetch_array($consulta_niveles)){
								if (($resultado_niveles['IdRol']>$_SESSION['idrol'])||($_SESSION['idrol']==1)){
									?><option <?php if ($resultado_niveles['IdRol']==5){echo "selected";}?>><?php echo $resultado_niveles['Nombre']?></option><?php
								}
							 }
							?> 
            			</select>
        				<input class="inline-form-button" type="submit" value="Registrar"/>
    				</form>

				</li>
				<li>Baja de Usuario 
					<form action="baja.php" method="post" >
						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
						<input class="inline-form-button" type="submit" value="Dar Baja"/><br />
						<label for="confirmabaja"><input type="checkbox" name="confirmabaja">Confirma Baja</label>
					</form>
				</li>
				<li>Busqueda de Usuarios
					<form target="_blank" action="busqueda.php" method="post" >
						<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
						<input class="inline-form-button" type="submit" value="Buscar"/><br />
					</form>
				</li>
			</ul>

			<?php
		}

		echo "<h3>GESTION DE TAREAS:</h3><br />";
		?>
		<ul>
			<li>Tareas asignadas
				<ul>
					<li>
						<form action="tareas.php" method="post" >
							<input class="inline-form-button" type="submit" value="Consultar..."/>
							<label for="solosincompletar"><input type="checkbox" name="solosincompletar">Sólo sin Completar</label>
						</form>
					</li>
					<!-- SOLO MOSTRAR EL SIGUIENTE ITEM SI LAS TAREAS ASIGNADAS >0 -->
					<li>
						<form action="diagrama.php" method="post">
							<input class="inline-form-button" type="submit" value="Diagrama..."/>
							<label for="solosincompletar"><input type="checkbox" name="solosincompletar">Sólo sin Completar</label>
						</form>
					</li>
				</ul>

			<!-- SOLO MOSTRAR SI  $_SESSION['idrol']<5-->
			<li>Nueva Tarea
				<form action="creartarea.php" method="post" >
					<ul>
						<li>
							<label for="taskstart">Nombre:<input type="text" maxlength=50 placeholder="Nombre de tarea" name="taskname" required /></label>
						</li>
						<li>
							<label for="taskstart">Inicio :<input type="date" name="taskstart" required /></label>
						</li>
						<li>
							<label for="taskstart">Mejor Fin :<input type="date" name="taskbestend" required /></label>
						</li>
						<li>
							<label for="taskstart">Peor Fin :<input type="date" name="taskworstend" required /></label>
						</li>
						<p><textarea maxlength=255 rows="5" cols="40" placeholder="Notas adicionales..." name="tasknotes" ></textarea></p>
						<input class="inline-form-button" type="submit" value="Crear tarea"/>
					</ul>
				</form>
			</li>
			<li>Busqueda de Tareas
				<input type="text" id="busquedaTareas" placeholder="Nombre de la tarea" />
				<div id="resultadoTareas"></div>
			</li>
		</ul>


	<p class="centrado">
		<a href="logout.php">CERRAR SESIÓN</a>
	</p>
			
		<?php

}else{
	?>
	<p class="error-centrado"><?php echo "No es un usuario registrado"; ?></p>
	<?php
		// $_SESSION=array();
		// session_destroy();
		include ("form_login.php");
}

?>

</body>
</html>