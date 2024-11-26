<?php session_start();?>
<?php

	include("../conexion.php");
	if (isset($_POST['taskid']) && isset($_POST['userid'])) {

		$asignador = $_SESSION['nombre'];
		$consulta = mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE Nombre = '$asignador'");
		$resultado = mysqli_fetch_array($consulta);
		$idasignador= $resultado['IdUsuario'];
	
		$idasignado=$_POST['userid'];
		// $consulta = mysqli_query($conexion, "SELECT Nombre FROM USUARIO WHERE IdUsuario = '$idasignado'");
		// $resultado = mysqli_fetch_array($consulta);
		// $asignado=$resultado['Nombre'];
	
		$taskId=$_POST['taskid'];
		// $consultaTarea=mysqli_query($conexion, "SELECT * FROM TAREA WHERE idTarea=$idtarea");
		// $resultadoTarea=mysqli_fetch_array($consultaTarea);
		// $inicio=$resultadoTarea['Inicio'];
		// $mejorfin=$resultadoTarea['MejorFin'];
		// $peorfin=$resultadoTarea['PeorFin'];
	
		$consulta=mysqli_query($conexion, "INSERT INTO ASIGNACIONDETAREA (USUARIO_IdUsuario, TAREA_IdTarea, idAsignador)
		VALUES('$idasignado','$taskId','$idasignador')");


		//lista de usuarios sin asignar
		$consultaIdDisponibles = mysqli_query($conexion, "SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre 
															FROM USUARIO u JOIN ROL r ON u.ROL_IdRol = r.IdRol 
															WHERE u.IdUsuario NOT IN (SELECT asig.USUARIO_IdUsuario FROM ASIGNACIONDETAREA asig WHERE asig.TAREA_IdTarea = $taskId)");
		$disponiblesHtml = '';
		while ($resultado = mysqli_fetch_array($consultaIdDisponibles)) {
			$disponiblesHtml .= '<tr>
				<td class="campo-resultados">' . $resultado['Nombre'] . '</td>
				<td class="campo-resultados">' . $resultado['ROL_Nombre'] . '</td>
				<td class="campo-resultados">
					<button type="button" class="btn btn-primary asignar" data-taskid="' . $taskId . '" data-userid="' . $resultado['IdUsuario'] . '">Asignar</button>
				</td>
			</tr>';
		}

		//lista de usuarios asignados
		$consultaIdAsignados = mysqli_query($conexion, "SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre, a.Nombre AS nombreAsignador
		FROM USUARIO u
		JOIN ROL r ON u.ROL_IdRol = r.IdRol
		JOIN ASIGNACIONDETAREA asig ON u.IdUsuario = asig.USUARIO_IdUsuario
		JOIN USUARIO a ON a.IdUsuario = asig.idAsignador
		WHERE asig.TAREA_IdTarea = $taskId");

		$asignadosHtml = '';
		while ($resultado = mysqli_fetch_array($consultaIdAsignados)) {
			$asignadosHtml .= '<tr>
				<td class="campo-resultados">' . $resultado['Nombre'] . '</td>
				<td class="campo-resultados">' . $resultado['ROL_Nombre'] . '</td>
				<td class="campo-resultados">' . $resultado['nombreAsignador'] . '</td>
				<td class="campo-resultados">
					<button type="button" class="btn btn-danger desasignar" data-taskid="' . $taskId . '" data-userid="' . $resultado['IdUsuario'] . '">Desasignar</button>
				</td>
			</tr>';
		}
	
		// envio las tablas en json
		echo json_encode([
			'disponibles' => $disponiblesHtml,
			'asignados' => $asignadosHtml
		]);

		
	}
	mysqli_close($conexion);
	?>
	