<?php session_start();?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Kumar+One&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">


<title>Tareas Asignadas</title>
</head>

<body>
	<?php
		include('conexion.php');
		$idUsuario = $_SESSION['idusuario'];
		$completadas='';
		if(isset($_POST['solosincompletar'])){
			$completadas=" AND T.Completada=0";
		}
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


	<section>
		<h3 class="centrado">Tareas asignadas</h3>
		<article>				
			<p>	
				<table class="table table-striped">
					<thead class="thead-light">
						<tr>
							<th class="campo-resultados">Nombre</th>
							<th class="campo-resultados columna-ajustada">Inicio</th>
							<th class="campo-resultados columna-ajustada">Mejor Fin</th>
							<th class="campo-resultados columna-ajustada">Peor Fin</th>							
							<th class="campo-resultados columna-ajustada">Asignada por</th>
							<th class="campo-resultados">Finalizada</th>
							<th class="campo-resultados"></th>
						</tr>							
					</thead>
					<tbody>
						<?php
						$consulta="SELECT T.Nombre, T.Inicio, T.MejorFin, T.PeorFin, 
							U.Nombre AsignadorNombre, 
							T.Completada, T.idTarea
						FROM ASIGNACIONDETAREA A
						JOIN TAREA T ON A.TAREA_IdTarea = T.idTarea
						JOIN USUARIO U ON A.idAsignador = U.idUsuario
						WHERE A.USUARIO_IdUsuario=?
						AND T.eliminada!=true".$completadas." ORDER BY T.Inicio DESC";
						$consultaSegura=$conexion->prepare($consulta);

						if ($consultaSegura === false) {
							die("Error en la preparación de la consulta: " . $conexion->error);
						}

						$consultaSegura->bind_param("i", $idUsuario);
						$consultaSegura->execute();
						$resultado = $consultaSegura->get_result();

						if($resultado->num_rows>0){
							while($fila=$resultado->fetch_assoc()){

								$checked='';
								if($fila['Completada']!='0'){
									$checked='checked';
								}
						?>
						<tr>
							<td class="campo-resultados"><?php echo $fila['Nombre']?></td>
							<td class="campo-resultados columna-ajustada"><?php echo $fila['Inicio']?></td>
							<td class="campo-resultados columna-ajustada"><?php echo $fila['MejorFin']?></td>
							<td class="campo-resultados columna-ajustada"><?php echo $fila['PeorFin']?></td>
							<td class="campo-resultados"><?php echo $fila['AsignadorNombre']?></td>
							<td class="campo-resultados">
								<input type="checkbox" class="checkbox-tarea" data-id="<?php 
								echo $fila['idTarea'] ?>" <?php echo $checked?> ></td>
							<td class="campo-resultados">
								<form action="editarTarea.php" method="POST" target="_blank">
									<input type="hidden" name="taskid" value=<?php echo $fila['idTarea'] ?>>
									<button type="submit" class="btn btn-primary" >EDITAR...</button>
								</form>
							</td>
						</tr>
						<?php									
							}
						}
						?>
					</tbody>
				</table>
			</p>
		</article>
	</section>


	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
	$(document).ready(function() {
		$('.checkbox-tarea').change(function() {
			var tareaId = $(this).data('id');
			var estado = $(this).is(':checked') ? 1 : 0;

			$.ajax({
				url: 'actualizar_tarea.php',
				type: 'POST',
				data: {
					id: tareaId,
					estado: estado
				},
				success: function(response) {
					console.log('Tarea actualizada: ' + response);
				},
				error: function(xhr, status, error) {
					console.error('Error al actualizar la tarea: ' + error);
				}
			});
		});
	});
	</script>


	<script>
	function actualizarMensajes() {
			$.ajax({
				url: 'ajax/contar_mensajes.php',
				type: 'POST',
				data: { idUsuario: <?php echo $idUsuario; ?> },
				dataType: 'json',
				success: function(data) {
					var cantidad = data.cantidad;
					$('#cantidad-mensajes').text(cantidad);
					
					var plural = (cantidad != 1) ? 's' : '';
					$('#enlace-mensajes').text('Mensaje' + plural);
					
					if (cantidad > 0) {
						$('#icono-mensaje').show();
					} else {
						$('#icono-mensaje').hide();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error("Error en la solicitud AJAX: " + textStatus, errorThrown);
				}
			});
		}

		setInterval(actualizarMensajes, 5000);
	</script>


</body>
</html>




