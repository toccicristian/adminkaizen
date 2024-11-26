<?php session_start();?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Kumar+One&family=Outfit:wght@100..900&display=swap" rel="stylesheet">


	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript" src="./js/busqueda.js"></script>

	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.12.0/css/all.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="css/estilos.css">

	<title>Admin Kaizen - Mensajes</title>
</head>
	
	<body>
<?php
		include("conexion.php");
		$idUsuario=$_SESSION['idusuario'];
		$consultaMensajes=mysqli_query($conexion,"SELECT M.idMensaje, M.asunto, M.cuerpo, M.leido, M.eliminado, M.fecha, U.Nombre AS remitente
                                                FROM `MENSAJES` M 
						JOIN USUARIO U ON M.idRemitente=U.IdUsuario
                                                WHERE M.idReceptor='$idUsuario' ORDER BY M.idMensaje DESC");
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

		<article>
			<p>
				<table class="table table-striped">
					<thead class="thead-light">
						<tr>
							<th class='campo-resultado'>Fecha</th>
							<th class='campo-resultados'>Remitente</th>
							<th class='campo-resultados'>Asunto</th>
							<th class='campo-resultados'>Mensaje</th>
						</tr>
					</thead>
					<tbody>
					<?php
					while($msgArr=mysqli_fetch_array($consultaMensajes)){
?>
					<tr>
						<?php 
							$strong="";
							$strongCierre="";
							if($msgArr['leido']==0){
								$strong="<strong>";
								$strongCierre="</strong>";
							}
						?>
                                                <td><?php echo $strong.$msgArr['fecha'].$strongCierre?></td>
                                                <td><?php echo $strong.$msgArr['remitente'].$strongCierre?></td>
                                                <td><?php echo $strong.$msgArr['asunto'].$strongCierre?></td>
                                                <td><?php echo $strong.$msgArr['cuerpo'].$strongCierre?></td>

                                                <?php
							$idMsg=$msgArr['idMensaje'];
	                                                mysqli_query($conexion,"UPDATE MENSAJES SET MENSAJES.leido=1 WHERE MENSAJES.idMensaje='$idMsg'");
                                                ?>

                                        </tr>

<?php
					}
?>
					</tbody>


				</table>
			</p>
		</article>

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
