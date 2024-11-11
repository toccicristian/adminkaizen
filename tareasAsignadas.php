<?php session_start();?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
			<h3>Tareas asignadas para Usuario <?php echo $_SESSION['nombre']; ?></h3>
			<article class="tabla-resultados">
				<p>	
					<table>
						<tr>
							<th class="campo-resultados">Nombre</th>
							<th class="campo-resultados">Inicio</th>
							<th class="campo-resultados">Mejor Fin</th>
							<th class="campo-resultados">Peor Fin</th>							
							<th class="campo-resultados">Asignada por</th>
							<th class="campo-resultados">Finalizada</th>
						</tr>
<?php
							$consulta=mysqli_query($conexion,
								"SELECT T.Nombre, T.Inicio, T.MejorFin, T.PeorFin, U.Nombre AsignadorNombre, T.Completada, T.idTarea
								FROM TAREA T, USUARIO U 
								WHERE T.idTarea IN (SELECT ASIG.TAREA_IdTarea as TAR FROM ASIGNACIONDETAREA ASIG WHERE ASIG.USUARIO_IdUsuario='$idUsuario') 
								AND U.IdUsuario IN (SELECT ASIG.idAsignador as ASIGNADOR FROM ASIGNACIONDETAREA ASIG WHERE ASIG.USUARIO_IdUsuario='$idUsuario')
								".$completadas);
							if(mysqli_num_rows($consulta)!=0){
								while($resultado=mysqli_fetch_array($consulta)){

									$checked='';
									if($resultado['Completada']!='0'){
										$checked='checked';
									}
?>
						<tr>
							<td class="campo-resultados"><?php echo $resultado['Nombre']?></td>
							<td class="campo-resultados"><?php echo $resultado['Inicio']?></td>
							<td class="campo-resultados"><?php echo $resultado['MejorFin']?></td>
							<td class="campo-resultados"><?php echo $resultado['PeorFin']?></td>
							<td class="campo-resultados"><?php echo $resultado['AsignadorNombre']?></td>
							<td class="campo-resultados">
								<input type="checkbox" class="checkbox-tarea" data-id="<?php 
								echo $resultado['idTarea'] ?>" <?php echo $checked?> ></td>
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




