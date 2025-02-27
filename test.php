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


<!-- default el primero
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
-->

<script type="text/javascript" src="./js/busqueda.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">





<title>test</title>
</head>

<body>


	<button type="button" class="btn btn-primary mx-4" data-toggle="modal" data-target="#crearTareaModal">	
		Nueva Tarea...
	</button>


									<!-- MODAL PARA CREAR TAREA -->

	<div class="modal fade" id="crearTareaModal" tabindex="-1" role="dialog" aria-labelledby="crearTareaModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="crearTareaModalLabel">Crear Tarea</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="crearTareaForm">
						<div class="form-group">
							<label for="tarea-name" class="col-form-label">Nombre de la Tarea:</label>
							<input type="text" class="form-control" id="tarea-name" name="nombretarea" required>
						</div>
						<div class="form-group">
							<label for="task-startdate" class="col-form-label">Fecha de Inicio:</label>
							<input type="date" class="form-control" id="task-startdate" name="startdate" required>
						</div>
						<div class="form-group">
							<label for="task-enddate" class="col-form-label">Fecha de Fin:</label>
							<input type="date" class="form-control" id="task-enddate" name="enddate" required>
						</div>
						<div class="form-group">
							<label for="task-notes" class="col-form-label">Notas Adicionales:</label>
							<textarea class="form-control" id="task-notes" name="notes"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Crear</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>									


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<script>
	//script para crear tarea    

		$(document).on('submit', '#crearTareaForm', function(e) {
			e.preventDefault();

			$.ajax({
				method: "POST",
				url: "ajax/crear_tarea.php",
				data: $(this).serialize(),
				success: function(data) {
					alert(data);
					$('#crearTareaModal').modal('hide'); // Cierra el modal después de crear la tarea
					// Aquí puedes agregar lógica para actualizar la lista de tareas si es necesario
				},
				error: function() {
					alert('Error al crear la tarea.');
				}
			});
		});
	</script>

</body>
</html>
