<?php session_start();?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Kumar+One&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<title>Admin Kaizen - Asignacion de Tarea</title>

</head>

<body>



	<?php
		include('conexion.php');

		if(isset($_SESSION['idtask'])){
			$idtarea=$_SESSION['idtask'];
			unset($_SESSION['idtask']);
		}else{
			if(isset($_POST['taskid'])){
				$idtarea=$_POST['taskid'];
			}else{
				header("Location:./login.php");
			}
			
		}

		$idUsuario=$_SESSION['idusuario'];
	?>

	<header>
		<h1>Admin Kaizen</h1>
		<nav class="navbar">
			<ul class="nav nav-tabs">
			</ul>


			<div class="isologo-small text-right">
				<p class="isotipo-index">Admin Kaizen</p>
				<a href="./index.php">
					<img class="main-logo-small" src="./imagenes/logo-200px.png" alt="adminkaizen logo">
				</a>
    		</div>
		</nav>
			

		<div class="alert alert-info" role="alert">
			<div class="notificaciones">
				Bienvenid@, <strong><?php echo $_SESSION['nombre']; ?></strong>! Sus Permisos son de: <strong><?php echo $_SESSION['rol']; ?></strong>.
			
				<?php
					$cuentaMensajesNuevos=mysqli_query($conexion,"SELECT COUNT(M.idMensaje) AS cantidad 
									FROM `MENSAJES` M 
									WHERE M.idReceptor='$idUsuario' AND LEIDO=0");
					$resultadoMensajesNuevos=mysqli_fetch_array($cuentaMensajesNuevos);
					$plural='';
					if ($resultadoMensajesNuevos['cantidad']!=1){
						$plural='s';
					}
				?>

				<h6 id="mensaje-notificacion">

					Tiene <span id="cantidad-mensajes"><?php echo $resultadoMensajesNuevos['cantidad']; ?></span> 
					<a id="enlace-mensajes" href="./mensajes.php" target="_blank">Mensaje<?php echo $plural; ?> </a> nuevo<?php echo $plural; ?>. 
					<a href="./mensajes.php" target="_blank" id="icono-mensaje"><img src="imagenes/email-static.png" alt="imagen de sobre" class="animacion-mail"></a>
					

				</h6>

				<?php
				if(isset($_SESSION['mensajesistema'])){
					echo "<p class='mensaje-sistema'>".$_SESSION['mensajesistema']."</p>";
					unset($_SESSION['mensajesistema']);
				}else{
					?><p class="mensaje-sistema">&nbsp</p><?php
				}
				?>

			</div>
			<p>
				<a href="logout.php" class="btn btn-info" role="button">CERRAR SESIÓN</a>
			</p>

		</div>			
	</header>


<?php

		

		//por comodidad visual, voy a pedir Nombre, inicio, mejor fin y peor fin
		$consultaTarea=mysqli_query($conexion,
			"SELECT t.Nombre, t.Inicio, t.MejorFin, t.PeorFin
			FROM TAREA t
			WHERE t.idTarea = '$idtarea'");
		$tarea = mysqli_fetch_array($consultaTarea);
?>
		
	<section class="area-tareas"> 
		
		<article class="transparencia tareas-asignadas col-12 mb-4 tarea-asignacion"> 
			<h3 class="mt-4 text-center">ASIGNACION DE LA TAREA:</h3> 
			<div class="p-3">
				<p class="font-weight-bold"><?php echo $tarea['Nombre'] ?></p>
				<p>
					Inicio : <?php echo $tarea['Inicio'] ?> 
					/ Mejor Fin : <?php echo $tarea['MejorFin'] ?> / Peor Fin : <?php echo $tarea['PeorFin'] ?>
					<?php 
					if(isset($_SESSION['mensajesistema'])){
						echo "-".$_SESSION['mensajesistema'];
						unset($_SESSION['mensajesistema']);
					}
					?>
				</p>
			</div>
		</article>	

		<div class="row">
			<article class="tabla-resultados transparencia tareas-asignadas col-md-5 mb-4">
				<p>	
					<h4>USUARIOS DISPONIBLES</h4>

					<table class="table table-stripped">
						<thead class="thead-light">
							<tr>
								<th class="campo-resultados">Nombre</th>
								<th class="campo-resultados">Permisos</th>
								<th class="campo-resultados"></th>
							</tr>
						</thead>
						<tbody>
							<?php
									
								$consultaIdDisponibles=mysqli_query($conexion,"SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre
																				FROM USUARIO u
																				JOIN ROL r ON u.ROL_IdRol = r.IdRol
																				WHERE u.IdUsuario NOT IN (
																					SELECT asig.USUARIO_IdUsuario
																					FROM ASIGNACIONDETAREA asig
																					WHERE asig.TAREA_IdTarea = $idtarea)");

								while($resultado=mysqli_fetch_array($consultaIdDisponibles)){
							?>	

							<tr>
								<td class="campo-resultados"><?php echo $resultado['Nombre']; ?></td>
								<td class="campo-resultados"><?php echo $resultado['ROL_Nombre']; ?></td>
								<td class="campo-resultados">
									<form action="asignartarea.php" method="post">
										<input type="hidden" name="taskid" value=<?php echo $idtarea ?>>
										<input type="hidden" name="userid" value=<?php echo $resultado['IdUsuario'] ?>>
										<button type="submit" class="btn btn-primary">Asignar</button>
									</form>
								</td>
							</tr>

							<?php
								}
							?>						
						</tbody>
					</table>
				</p>
			</article>

			<article class="tabla-resultados transparencia tareas-asignadas col-md-5 mb-4">
				<p>	
					<h4>USUARIOS ASIGNADOS</h4>
					<table class="table table-stripped">
						<thead class="thead-light">
							<tr>
								<th class="campo-resultados">Nombre</th>
								<th class="campo-resultados">Permisos</th>
								<th class="campo-resultados">Asignador</th>
								<th class="campo-resultados"></th>
							</tr>
						</thead>
						<tbody>
							<?php

								$consultaIdAsignados=mysqli_query($conexion,"SELECT u.IdUsuario, u.Nombre , r.Nombre AS ROL_Nombre, a.Nombre AS nombreAsignador
																			FROM USUARIO u
																			JOIN ROL r ON u.ROL_IdRol = r.IdRol
																			JOIN ASIGNACIONDETAREA asig ON u.IdUsuario = asig.USUARIO_IdUsuario
																			JOIN USUARIO a ON a.IdUsuario = asig.idAsignador
																			WHERE asig.TAREA_IdTarea = $idtarea;");

								while($resultado=mysqli_fetch_array($consultaIdAsignados)){
							?>	
						
							<tr>
								<td class="campo-resultados"><?php echo $resultado['Nombre']; ?></td>
								<td class="campo-resultados"><?php echo $resultado['ROL_Nombre']; ?></td>
								<td class="campo-resultados"><?php echo $resultado['nombreAsignador']; ?></td>
								<td class="campo-resultados">
									<form action="desasignartarea.php" method="post">
										<input type="hidden" name="taskid" value=<?php echo $idtarea ?>>
										<input type="hidden" name="userid" value=<?php echo $resultado['IdUsuario'] ?>>
										<button type="submit" class="btn btn-danger">Desasignar</button>
									</form>
								</td>
							</tr>

								<?php
								}
								?>
						</tbody>
					</table>
				</p>
			</article>



		</div>



	</section>

<?php
		mysqli_free_result($consultaTarea);
		mysqli_free_result($consultaIdAsignados);
		mysqli_free_result($consultaIdDisponibles);
		mysqli_close($conexion);
?>



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



</body>
</html>




