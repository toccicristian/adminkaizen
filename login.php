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


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">





<title>Admin Kaizen - Dashboard</title>
</head>

<body>

<?php

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
	
	$_SESSION['idusuario']=$respuesta['IdUsuario'];
	$_SESSION['nombre']=$respuesta['Nombre'];
	$_SESSION['email']=$respuesta['EMail'];
	$_SESSION['idrol']=$respuesta['IdRol'];
	$_SESSION['rol']=$respuesta['ROL_Nombre'];

	$idUsuario=$_SESSION['idusuario'];

		?>
		<header>
		<h1>Admin Kaizen</h1>
		<nav class="navbar">
			<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" href="#" onclick="showSection('usuarios')">Usuarios</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="showSection('tareas')">Tareas</a>
					</li>
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
		$consulta_idrol_max=mysqli_query($conexion, "SELECT MAX(IdRol) AS 'max_idrol' FROM ROL");
		$idrol_max=mysqli_fetch_array($consulta_idrol_max);
		?>
		<div id="usuarios" class="tab-content area-usuarios">
			<div class="gestion-usuarios">
			<?php			
			if((int)$_SESSION['idrol']<(int)$idrol_max['max_idrol']){
			?>
						
				<h3 class='centrado'>GESTIÓN DE USUARIOS</h3>
				<button type="button" class="btn btn-primary mx-4" data-toggle="modal" data-target="#altaModal">	
						Alta de Empleado
				</button>

			<?php
			}
			?>
				<section class="busqueda-usuarios">				
					<article class="usuarios">
						<p class="campo-busqueda">
							<input type="text" id="busquedaUsuarios" class="search-input" placeholder="Nombre de usuario a buscar" />
							<label for="mostrareliminados"><input type="checkbox" id="mostrareliminados" name="mostrareliminados">Mostrar eliminados</label>
						</p>
						<div class="resultadoUsuarios" id="resultadoUsuarios"></div>
					</article>
				</section>
			</div>
		</div>

		<div id="tareas" class="tab-content area-tareas" style="display: none;">		
			<section class="formulario-edicion">
				<h3 class="centrado">GESTION DE TAREAS</h3>
				<article class="tareas">
					<ul>
						<li>
							<form action="tareasAsignadas.php" method="post" target="_blank" class="boton-con-check transparencia tareas-asignadas">

								<input class="btn btn-primary mx4" type="submit" value="Consultar Tareas asignadas..."/>
								<label for="solosincompletar"><input type="checkbox" name="solosincompletar">Sólo sin Completar</label>
							</form>
								<!-- </li> -->

								<!-- SOLO MOSTRAR EL SIGUIENTE ITEM SI LAS TAREAS ASIGNADAS >0 -->
								<!-- <li>
									<form action="diagrama.php" method="post">
										<input class="inline-form-button" type="submit" value="Diagrama..."/>
										<label for="solosincompletar"><input type="checkbox" name="solosincompletar">Sólo sin Completar</label>
									</form>
								</li> -->
						
							<!-- </ul> -->
						</li>

				<!-- SOLO MOSTRAR SI  $_SESSION['idrol']<5-->
	<?php
				if ($_SESSION['idrol'] < $idrol_max['max_idrol']){
	?>	

						<li><h4>Nueva Tarea</h4>
							<form action="creartarea.php" method="post" class="transparencia tareas-asignadas">
								<ul>
									<li>
										<input type="text" name='taskname' class="form-control" placeholder="Nombre de la tarea" aria-label="Tarea" aria-describedby="basic-addon1" required>
									</li>
									<li>
										<label for="taskstart" class="label-fecha">Inicio </label>
										<input type="date" name="taskstart" required />
									</li>
									<li>
										<label for="taskstart" class="label-fecha">Mejor Fin </label>
										<input type="date" name="taskbestend" required />
									</li>
									<li>
										<label for="taskstart" class="label-fecha">Peor Fin </label>
										<input type="date" name="taskworstend" required />
									</li>
									<p><textarea maxlength=255 rows="5" cols="40" placeholder="Notas adicionales..." name="tasknotes" ></textarea></p>									
									<input class="btn btn-primary mx4" type="submit" value="Crear tarea"/>
									
								</ul>
							</form>
						</li>
					</ul>
					<aside class="busqueda-tareas">
						<h4>Búsqueda de Tareas</h4>
						<div class="transparencia tareas-asignadas">
							<div class="boton-con-check">
								<input type="text" id="busquedaTareas" placeholder="Nombre de la tarea" />
								<label for="mostrareliminadas"><input type="checkbox" id="mostrareliminadas" name="mostrareliminadas">Mostrar eliminadas</label>
							</div>

							<div id="resultadoTareas"></div>
						</div>

					</aside>

				<?php
				}
				?>
				</article>
			</section>

		</div>
			

			<!-- MODAL PARA ALTA DE USUARIOS -->

    <div class="modal fade" id="altaModal" tabindex="-1" role="dialog" aria-labelledby="altaModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="altaModalLabel">Alta de Usuario</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form id="formAlta">
        			<div class="form-group">
            			<label for="usuario" class="col-form-label">Usuario:</label>
						<div id="nombre-usuario"></div>
            			<input type="text" maxlength=12 placeholder="Nombre de usuario" required class="form-control" id="usuario" name="usuario">
          			</div>
					<div class="form-group">
	            		<label for="clave" class="col-form-label">Clave:</label>
            			<input type="password"  maxlength=12 placeholder="Clave" required class="form-control" id="clave" name="clave"></input>
          			</div>
                      <div class="form-group">
	            		<label for="email" class="col-form-label">Email:</label>
            			<input type="text" maxlength=80 placeholder="Correo" required class="form-control" id="email" name="email"></input>
                    </div>

                    <div class="form-group">
						<label for="nivel" class="col-form-label">Credenciales:</label>
						<select name="nivel" id=nivel class="form-control">
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
                    </div>            

		  			<div class="modal-footer">
	        			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        				<button type="submit" class="btn btn-primary">Registrarse</button>
      				</div>
        		</form>
    		</div>
    	</div>
  	</div>
    </div>



									<!-- MODAL PARA ENVIAR MENSAJES A USUARIOS -->

    <div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="sendMailModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="sendMailModalLabel">Enviar correo</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form id="sendMailForm">
        			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Destinatario:</label>
						<div id="destinatario"></div>
            			<input type="text" class="form-control" id="recipient-name" name="destinatario">
          			</div>
					<div class="form-group">
	            		<label for="message-subject" class="col-form-label">Asunto:</label>
            			<input type="text" class="form-control" id="message-subject" name="asunto"></textarea>
          			</div>
          			<div class="form-group">
	            		<label for="message-text" class="col-form-label">Mensaje:</label>
            			<textarea class="form-control" id="message-text" name="mensaje"></textarea>
          			</div>
		  			<div class="modal-footer">
	        			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        				<button type="submit" class="btn btn-primary">Enviar</button>
      				</div>
        		</form>
    		</div>
    	</div>
  	</div>
</div>







	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>




		<?php

}else{
		header("Location:./logout.php");
		// include ("form_login.php");
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

<script>
    $(document).ready(function(){
        $('#formAlta').on('submit', function(e) {
            e.preventDefault(); 

            var formData = $(this).serialize(); // Aplasto los datos del formulario

            $.ajax({
                url: 'ajax/reg.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response); 
                },
                error: function() {
                    alert('Error al dar el alta al usuario.'); 
                }
            });
        });
    });

</script>


<script>


	function loadUsers() {	
				var mostrarEliminados = $('#mostrareliminados').is(':checked') ? 'true' : 'false';
				var busqueda = $('#busquedaUsuarios').val();
				$.ajax({
					url: 'ajax/fetchUsers.php', 
					type: 'POST',
					data: {
						mostrareliminados: mostrarEliminados, 
						busqueda: busqueda 
					},
					success: function(data) {
						$('#resultadoUsuarios').html(data); 
					},
					error: function() {
						$('#resultadoUsuarios').html('Error al cargar los usuarios.'); 
					}
				});
			}

	function deleteUser(id) {
		if (confirm("Está seguro de que desea eliminar este usuario?")){
			$.ajax({
			url: 'ajax/baja.php', 
			type: 'POST',
			data: { id: id },
			success: function(response) {
				alert(response); 
				loadUsers(); 
			},
			error: function() {
				alert('Error al eliminar el usuario.'); 
			}
		});                
		}
		else{
			alert("El usuario no ha sido eliminado.");
		}

	}


</script>

<script>
    
    $(document).on('submit','#sendMailForm',function(e){
        e.preventDefault();
   
        $.ajax({
            method:"POST",
            url: "ajax/enviarmail.php",
            data:$(this).serialize(),
            success: function(data){
					alert(data);  
            }});
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
