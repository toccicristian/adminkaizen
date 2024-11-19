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


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">
<title>Admin Kaizen - Dashboard</title>
</head>

<body>

<?php

// if ((!isset($_POST['usuario']) || !isset($_POST['password'])) && (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])))
// {
// 	header("Location:./index.php");
// 	exit();
// }

// if(isset($_POST['usuario'])&&isset($_POST['password'])){
// 	$usuario = $_POST['usuario'];
// 	$password = md5($_POST['password']);
// }elseif((isset($_SESSION['usuario'])&&isset($_SESSION['password']))){
// 	$usuario = $_SESSION['usuario'];
// 	$password = md5($_SESSION['password']);
// }


if (isset($_SESSION['usuario'])){
	$usuario=$_SESSION['usuario'];
}

include("conexion.php");

$consulta=mysqli_query($conexion, "SELECT USUARIO.IdUsuario, USUARIO.Nombre, USUARIO.EMail, ROL.IdRol, ROL.Nombre ROL_Nombre 
									FROM USUARIO 
									INNER JOIN ROL
										ON USUARIO.ROL_IdRol = ROL.IdRol
									WHERE USUARIO.Nombre='$usuario'");
//le puse ROL_Nombre a la columna ROL.Nombre porque la tabla usuario tenia una columna Nombre.

if(mysqli_num_rows($consulta)!=0){
	$respuesta=mysqli_fetch_array($consulta);
	
	// if(!isset($_SESSION['usuario']) && !isset($_SESSION['password'])){
	// 	$_SESSION['usuario']=$respuesta['Nombre'];
	// 	$_SESSION['password']=$_POST['password'];
	// }

	$_SESSION['idusuario']=$respuesta['IdUsuario'];
	$_SESSION['nombre']=$respuesta['Nombre'];
	$_SESSION['email']=$respuesta['EMail'];
	$_SESSION['idrol']=$respuesta['IdRol'];
	$_SESSION['rol']=$respuesta['ROL_Nombre'];

	$idUsuario=$_SESSION['idusuario'];

		?>
		<header>
			<div class="isologo-small text-right">
				<p class="isotipo-index">Admin Kaizen</p>
				<a href="./index.php">
					<img class="main-logo-small" src="./imagenes/logo-200px.png" alt="adminkaizen logo">
				</a>
    		</div>

			<h1>Admin Kaizen</h1>
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#" onclick="showSection('usuarios')">Usuarios</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" onclick="showSection('tareas')">Tareas</a>
				</li>
			</ul>

			<div class="alert alert-info mt-3" role="alert">
        		Bienvenid@, <strong><?php echo $_SESSION['nombre']; ?></strong>! Sus Permisos son de: <strong><?php echo $_SESSION['rol']; ?></strong>.
		    
			<?php
				$cuentaMensajesNuevos=mysqli_query($conexion,"SELECT COUNT(M.idMensaje) AS cantidad 
								FROM `MENSAJES` M 
								WHERE M.idReceptor='$idUsuario' AND LEIDO=0");
				$resultadoMensajesNuevos=mysqli_fetch_array($cuentaMensajesNuevos);
			?>
				<h6>Tiene <?php echo $resultadoMensajesNuevos['cantidad']; ?> <a href="./mensajes.php" target="_blank">Mensajes</a> nuevos. </h6>
			</div>			
		</header>
		<?php



		if(isset($_SESSION['mensajesistema'])){
			echo "<p class='mensaje-sistema'>".$_SESSION['mensajesistema']."</p>";
			unset($_SESSION['mensajesistema']);
		}

		$consulta_idrol_max=mysqli_query($conexion, "SELECT MAX(IdRol) AS 'max_idrol' FROM ROL");
		$idrol_max=mysqli_fetch_array($consulta_idrol_max);


		?>
		<div id="usuarios" class="tab-content">
		<?php

			if((int)$_SESSION['idrol']<(int)$idrol_max['max_idrol']){
				echo "<h3 class='centrado'>GESTIÓN DE USUARIOS:</h3><br />";
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

				</ul>

				<?php
			}

			?>


			<section class="formulario-edicion">
				<h4 class="centrado">Búsqueda de Usuarios</h4>
				<article class="tareas">
					<p>
						<form target="_blank" action="busqueda.php" method="post" >
							<input type="text" maxlength=12 placeholder="Nombre de usuario" name="usuario" required />
							<input class="inline-form-button" type="submit" value="Buscar"/><br />
						</form>
					</p>
				</article>
			</section>
		</div>

		<div id="tareas" class="tab-content" style="display: none;">		
			<section class="formulario-edicion">
				<h3 class="centrado">GESTION DE TAREAS</h3>
				<article class="tareas">
					<ul>
						<li><h4>Tareas asignadas</h4> 
							<ul>
								<li>
									<form action="tareasAsignadas.php" method="post" target="_blank">
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
	<?php
				if ($_SESSION['idrol'] < $idrol_max['max_idrol']){
	?>	

						<li><h4>Nueva Tarea</h4>
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
					</ul>
					<aside class="busqueda-tareas">
						<h4>Búsqueda de Tareas</h4>
						<input type="text" id="busquedaTareas" placeholder="Nombre de la tarea" />
						<label for="mostrareliminadas"><input type="checkbox" id="mostrareliminadas" name="mostrareliminadas">Mostrar eliminadas</label>
						<div id="resultadoTareas"></div>
					</aside>

				<?php
				}
				?>
				</article>
			</section>

		</div>
	<p class="centrado">
		<a href="logout.php">CERRAR SESIÓN</a>
	</p>
			
		<?php

}else{
	?>
	<p class="error-centrado"><?php echo "No es un usuario registrado"; ?></p>
	<?php
		include ("form_login.php");
}

?>


<script>
	function showSection(section) {
		document.querySelectorAll('.tab-content').forEach(function(tab) {
			tab.style.display = 'none';
		});

		document.getElementById(section).style.display = 'block';

		document.querySelectorAll('.nav-link').forEach(function(link) {
			link.classList.remove('active');
		});
		document.querySelector(`a[onclick="showSection('${section}')"]`).classList.add('active');
	}

	document.addEventListener('DOMContentLoaded', function() {
		showSection('usuarios');
	});
</script>

</body>
</html>
