<?php session_start();?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
			//$sinCompletar = $_POST['solosincompletar'];
		}
		/*
		else{
			$sinCompletar=0;
		}
		*/
		
?>
		<section>
			<h3 class="centrado">Tareas asignadas para Usuario <?php echo $_SESSION['nombre']; ?></h3>
			<article class="tabla-resultados">
				<p>	
					<table>
						<tr>
							<th class="campo-resultados">Nombre</th>
							<th class="campo-resultados columna-ajustada">Inicio</th>
							<th class="campo-resultados columna-ajustada">Mejor Fin</th>
							<th class="campo-resultados columna-ajustada">Peor Fin</th>							
							<th class="campo-resultados columna-ajustada">Asignada por</th>
							<th class="campo-resultados">Finalizada</th>
							<th class="campo-resultados"></th>
						</tr>
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



</body>
</html>




