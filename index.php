<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<title>Admin Kaizen - Inicio</title>
</head>

<body>
	<header>
		<h1>Admin Kaizen</h1>
		<nav class="navbar">
			<div class="menu">  
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">
							Registrarse
				</button>
				<a href="./form_login.php" class="menu-item">Iniciar sesión</a>
			</div>
			<div class="isologo-small">
				<a href="./index.php"><img class="main-logo-small" src="./imagenes/logo-200px.png" alt="adminkaizen logo"></img></a>
				<p class="isotipo-index">Admin Kaizen</p>
			</div>

		</nav>
    </header>
    <article>
        <?php 
            if(isset($_SESSION['mensajesistema'])){
                ?><section class="seccion-inicio">
                <?php
                echo "<p class='mensaje-sistema'>".$_SESSION['mensajesistema']."</p>";
                unset($_SESSION['mensajesistema']);
                ?></section>
                <?php
            }
        ?>        
        </section>
        <section class="seccion-inicio">
            <div class="producto-detalles">
                <p class="producto-descripcion">
                    <h3>Plataforma de gestión de actividades</h3>
                    <p>
                        Admin Kaizen es una plataforma de administración de actividades diseñada 
                        para optimizar la coordinación y gestión jerarquica de proyectos. 
                        Ofrece una interfaz intuitiva y fácil de usar, que permite a los usuarios gestionar tareas, 
                        programar eventos, asignar recursos y supervisar el progreso de los proyectos en tiempo real. 
                    </p>
                </p>
            </div>
            <aside>
                <img src="./imagenes/preview.png" alt="screenshot del producto" class="producto-screenshot">
            </aside> 
        </section>
    </article>


    <?php include('conexion.php'); ?>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="registerModalLabel">Registro de Socio</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form action="registro.php" method="post">
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
                    <label for="email" class="col-form-label"></label>
                        <?php
                            $consulta_nivel2=mysqli_query($conexion, "SELECT Nombre FROM ROL WHERE IdRol=2");
                            $resultado_nivel2=mysqli_fetch_array($consulta_nivel2);
                        ?>
                        <?php echo '<input type="hidden" name="nivel" value="'.$resultado_nivel2['Nombre'].'"></input>'?>
            			
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


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>








</body>
</html>