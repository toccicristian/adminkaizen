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

        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->

        <link rel="stylesheet" href="css/estilos.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Edición de Tarea</title>
    </head>

    <body>
        <?php
            include('conexion.php');
            if (isset($_POST['taskid'])){
                $_SESSION['taskid']=$_POST['taskid'];
            }
            $idTarea=$_SESSION['taskid'];
            $idUsuario=$_SESSION['idusuario'];
        ?>

    <header>
		<h1>Admin Kaizen</h1>
		<nav class="navbar">
			<ul class="nav nav-tabs">
					<!-- <li class="nav-item">
						<a class="nav-link active" href="#" onclick="showSection('usuarios')">Usuarios</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="showSection('tareas')">Tareas</a>
					</li> -->
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



        <div class="area-tareas">
            <section class="formulario-edicion">
                <?php
                    $consulta=mysqli_query($conexion,"SELECT Nombre, Notas FROM TAREA WHERE TAREA.idTarea='$idTarea'");
                    $tarea=mysqli_fetch_array($consulta);
                    mysqli_free_result($consulta);
                ?>


                <div class="row">
                    <div class="col-md-6">
                        <!-- Formulario de Notas -->
                        <div class="card mb-3 transparencia tareas-asignadas">
                            <div class="card-body">
                                <h3 class="card-title">EDICION DE LA TAREA:</h3> 

                                <p class="card-text"><?php echo $tarea['Nombre'] ?></p>
                                <p class="card-text">Usuarios Asignados: 
                                    <?php
                                        $consulta=mysqli_query($conexion,
                                            "SELECT U.Nombre, T.Nombre AS Tarea_Nombre
                                            FROM USUARIO U, TAREA T
                                            WHERE U.IdUsuario IN (
                                                SELECT A.USUARIO_IdUsuario 
                                                FROM `ASIGNACIONDETAREA` A 
                                                WHERE A.TAREA_IdTarea='$idTarea'
                                            )
                                            AND T.idTarea='$idTarea'
                                        ");

                                        $nombres = array(); 
                                        while ($fila = mysqli_fetch_array($consulta)) {
                                            $nombres[] = $fila['Nombre']; 
                                        }
                                        $usuariosAsignados = implode(", ", $nombres);
                                        echo "<strong>".$usuariosAsignados."</strong>";
                                        mysqli_free_result($consulta);
                                    ?>
                                </p>
                                <p class="card-text">
                                    <?php 
                                                if(isset($_SESSION['mensajesistema'])){
                                                    echo "-".$_SESSION['mensajesistema'];
                                                    unset($_SESSION['mensajesistema']);
                                                }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="card mb-3 transparencia tareas-asignadas">
                            <div class="card-body">
                                <article>
                                    <form id="formNotas">
                                        <p><textarea maxlength=255 rows="5" cols="40" placeholder="Notas adicionales..." name="tasknotes" id="tasknotes"><?php echo $tarea['Notas']; ?></textarea></p>
                                        <input type="hidden" name="taskid" id="taskid" value="<?php echo $idTarea; ?>">
                                        <button type="submit" class="btn btn-success" >Guardar Cambios</button>      
                                    </form>
                                </article>

                            </div>

                        </div>
                    </div>


                    <div class="col-md-6">

                        <article class="area-documentos">

                            <div class="card mb-3 transparencia tareas-asignadas">
                                <div class="card-body">
                                    <h3>Subir un Documento</h3>
                                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                                        <!-- <input type="file" name="file" id="file" required> -->
                                         <div class="mb-3">
                                            <input class="form-control mb-3" type="file" id="file" name="file">
                                         </div>
                                        <input type="text" name="detalle" id="detalle" placeholder="Detalle" required>
                                        <input type="hidden" name="tarea_id" id="tarea_id" value="<?php echo $idTarea ?>">
                                        <input type="submit" value="Subir Documento">
                                    </form>
                                    <div id="response"></div>
                                </div>
                            </div>


                            <div class="card mb-3 transparencia tareas-asignadas">
                                <div class="card-body">
                                    <div>
                                        <h3>Documentos Subidos</h3>
                                        <div id="documentList"></div>
                                    </div>
                                </div>
                            </div>

                        </article>                             
                    </div>
                </div>

            </section>

        </div>




        <script>
            $(document).ready(function() {  

                loadDocuments();

                $('#uploadForm').on('submit', function(e) {
                    e.preventDefault(); 

                    var formData = new FormData(this); 

                    $.ajax({
                        url: 'ajax/upload.php', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false, 
                        success: function(response) {
                            $('#response').html(response); 
                            loadDocuments(); 
                        },
                        error: function() {
                            $('#response').html('Error al subir el documento.'); 
                        }
                    });
                });
            
                // Para actualizar las notas!
                $('#formNotas').on('submit', function(e) {
                    e.preventDefault(); 

                    var formData = $(this).serialize(); // Aplasto los datos del formulario

                    $.ajax({
                        url: 'ajax/update_notas.php', 
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            alert(response); 
                        },
                        error: function() {
                            alert('Error al actualizar las notas.'); 
                        }
                    });
                });
            });

            function loadDocuments() {
                $.ajax({
                    url: 'ajax/fetch_documents.php', 
                    type: 'GET',
                    success: function(data) {
                        $('#documentList').html(data); 
                    },
                    error: function() {
                        $('#documentList').html('Error al cargar documentos.'); 
                    }
                });
            }

            function deleteDocument(id) {
                if (confirm("Está seguro de que desea eliminar este documento?")){
                    $.ajax({
                    url: 'ajax/delete_document.php', 
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        alert(response); 
                        loadDocuments(); 
                    },
                    error: function() {
                        alert('Error al eliminar el documento.'); 
                    }
                });                
                }
                else{
                    alert("El documento no ha sido eliminado.");
                }

            }

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