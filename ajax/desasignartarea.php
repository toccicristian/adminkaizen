<?php session_start();?>
<?php
include('../conexion.php');

if (isset($_POST['taskid']) && isset($_POST['userid'])) {
    $taskId = $_POST['taskid'];
    $userId = $_POST['userid'];

    // Lógica para desasignar el usuario de la tarea

    $query = "DELETE FROM ASIGNACIONDETAREA WHERE TAREA_IdTarea = '$taskId' AND USUARIO_IdUsuario = '$userId'";
    mysqli_query($conexion, $query);

    // Obtener la nueva lista de usuarios disponibles
    $consultaIdDisponibles = mysqli_query($conexion, "SELECT u.IdUsuario, u.Nombre, r.Nombre AS ROL_Nombre FROM USUARIO u JOIN ROL r ON u.ROL_IdRol = r.IdRol WHERE u.IdUsuario NOT IN (SELECT asig.USUARIO_IdUsuario FROM ASIGNACIONDETAREA asig WHERE asig.TAREA_IdTarea = $taskId)");
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

    // Obtener la nueva lista de usuarios asignados
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
	
		// Devolver las tablas actualizadas como respuesta JSON
		echo json_encode([
			'disponibles' => $disponiblesHtml,
			'asignados' => $asignadosHtml
		]);

		
	}
	mysqli_close($conexion);
	?>
	