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


<title>Resultados de Búsqueda</title>
</head>

<body>
<section>
	<?php
		include('conexion.php');

		$usuario = $_POST['usuario'];
		$consulta=mysqli_query($conexion, 
			"SELECT USUARIO.Nombre, USUARIO.EMail, ROL.Nombre Permiso FROM USUARIO
			INNER JOIN ROL ON USUARIO.ROL_IdRol = ROL.IdRol
			WHERE USUARIO.Nombre LIKE '%$usuario%'
			ORDER BY USUARIO.Nombre");

		if(mysqli_num_rows($consulta)!=0){
			echo "Mostrando ".mysqli_num_rows($consulta)." resultados para: <em>".$usuario."</em><br />";
	?>
	<div id='mensajito'></div>
	<article class="tabla-resultados">
		<p>	
			<table>
				<tr>
					<th class="campo-resultados">Nombre</th>
					<th class="campo-resultados">Permisos</th>
					<th class="campo-resultados"></th>
				</tr>

	<?php
			while($resultado=mysqli_fetch_array($consulta)){
	?>	

				<tr>
					<td class="campo-resultados"><?php echo $resultado['Nombre']; ?></td>
					<td class="campo-resultados"><?php echo $resultado['Permiso']; ?></td>
					<td class="campo-resultados">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendMailModal" data-whatever="<?php echo $resultado['EMail']?>">
							<img src='./imagenes/email.png' alt='icono de email' class='icono'>
						</button>
					</td>
				</tr>

	<?php
			}
	?>

			</table>
		</p>

	<?php
			mysqli_free_result($consulta);
			mysqli_close($conexion);
		}else{
			echo "No se han encontrado resultados para <em>".$usuario."</em><br />";
		}
	?>

	</article>
</section>

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


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>




</body>
</html>




