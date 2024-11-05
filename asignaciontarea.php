<?php session_start();?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<script>
	$(document).ready(function(e){
		$('#sendMailModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) 
		var recipient = button.data('whatever')
		var modal = $(this)
		modal.find('.modal-title').text('Nuevo mensaje...')
		modal.find('.modal-body input').val(recipient)
		modal.find('.modal-body input').prop('disabled', true)
		})
	})

</script>


<title>Asignacion de Tarea</title>
</head>

<body>


<?php
		include('conexion.php');
		$idtarea=$_POST['taskid'];


		//por comodidad visual, voy a pedir Nombre, inicio, mejor fin y peor fin
		$consultaTarea=mysqli_query($conexion,
			"SELECT t.Nombre, t.Inicio, t.MejorFin, t.PeorFin
			FROM TAREA t
			WHERE t.idTarea = '$idtarea'");
		$tarea = mysqli_fetch_array($consultaTarea);
?>
		
	<section> 
		<h3>ASIGNACION DE LA TAREA:</h3> 
		<p><?php echo $tarea['Nombre'] ?></p>
		<p>
			Inicio : <?php echo $tarea['Inicio'] ?> 
			/ Mejor Fin : <?php echo $tarea['MejorFin'] ?> / Peor Fin : <?php echo $tarea['PeorFin'] ?>
		</p>

		<article class="tabla-resultados">
			<p>	
				<h4>USUARIOS ASIGNADOS</h4>
				<table>
					<tr>
						<th class="campo-resultados">Nombre</th>
						<th class="campo-resultados">Permisos</th>
						<th class="campo-resultados"></th>
					</tr>

<?php
		$consultaIdAsignados=mysqli_query($conexion,"SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre
		FROM USUARIO u
		JOIN ROL r ON u.ROL_IdRol = r.IdRol
		JOIN ASIGNACIONDETAREA a ON u.IdUsuario = a.USUARIO_IdUsuario
		WHERE a.TAREA_IdTarea = 17;");
		while($resultado=mysqli_fetch_array($consultaIdAsignados)){
?>	

					<tr>
						<td class="campo-resultados"><?php echo $resultado['Nombre']; ?></td>
						<td class="campo-resultados"><?php echo $resultado['ROL_Nombre']; ?></td>
						<td class="campo-resultados">
							<form action="desasignar.php" method="post">
								<input type="hidden" name="taskid" value=<?php echo $idtarea ?>>
								<input type="hidden" name="userid" value=<?php echo $resultado['IdUsuario'] ?>>
								<button type="submit" class="btn btn-danger">Desasignar</button>
							</form>
						</td>
					</tr>

<?php
		}
?>

				</table>
			</p>
		</article>


		<article class="tabla-resultados">
			<p>	
				<h4>USUARIOS DISPONIBLES</h4>
				<table>
					<tr>
						<th class="campo-resultados">Nombre</th>
						<th class="campo-resultados">Permisos</th>
						<th class="campo-resultados"></th>
					</tr>

<?php
		
		$consultaIdDisponibles=mysqli_query($conexion,"SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre
													FROM USUARIO u 
													JOIN ROL r ON u.ROL_IdRol = r.IdRol
													LEFT JOIN ASIGNACIONDETAREA a ON u.IdUsuario = a.USUARIO_IdUsuario 
													AND (a.TAREA_IdTarea = 17 OR a.TAREA_IdTarea IS NULL)
													WHERE a.USUARIO_IdUsuario IS NULL OR a.TAREA_IdTarea != $idtarea;");

		while($resultado=mysqli_fetch_array($consultaIdDisponibles)){
?>	

					<tr>
						<td class="campo-resultados"><?php echo $resultado['Nombre']; ?></td>
						<td class="campo-resultados"><?php echo $resultado['ROL_Nombre']; ?></td>
						<td class="campo-resultados">
							<form action="desasignar.php" method="post">
								<input type="hidden" name="taskid" value=<?php echo $idtarea ?>>
								<input type="hidden" name="userid" value=<?php echo $resultado['IdUsuario'] ?>>
								<button type="submit" class="btn btn-primary">Asignar</button>
							</form>
						</td>
					</tr>

<?php
		}
?>

				</table>
			</p>
		</article>
	</section>
<?php
		mysqli_free_result($consultaTarea);
		mysqli_free_result($consultaIdAsignados);
		mysqli_free_result($consultaIdDisponibles);
		mysqli_close($conexion);
?>

<!-- 
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
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Destinatario:</label>
			<div id="destinatario"></div>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Mensaje:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div> 
-->


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>




</body>
</html>




