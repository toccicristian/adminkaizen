<?php session_start();
include("../conexion.php"); // Asegúrate de que la ruta sea correcta

if (isset($_POST['search'])) {

    $idrol = $_SESSION['idrol'];
    $nombreUsuario = $_POST['search'];
    $mostrarEliminados = isset($_POST['mostrareliminados']) && $_POST['mostrareliminados'] === 'true';

    $stmt = $conexion->prepare("SELECT U.IdUsuario, U.Nombre, U.Email, U.ROL_IdRol, R.Nombre as ROL_Nombre, U.eliminado
                                 FROM USUARIO U
                                 JOIN ROL R ON U.ROL_IdRol = R.IdRol
                                 WHERE U.Nombre LIKE ? AND (U.eliminado = 0 OR ?)
                                 LIMIT 20");
    $searchTerm = "%$nombreUsuario%";
    $stmt->bind_param("si", $searchTerm, $mostrarEliminados);
    $stmt->execute();
    $result = $stmt->get_result();

    ?>

<script>

$(document).ready(function(e){
		$('#sendMailModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) 
		var recipient = button.data('whatever')
		var modal = $(this)
		modal.find('.modal-title').text('Nuevo mensaje...')
		modal.find('#recipient-name').val(recipient)
		modal.find('#recipient-name').prop('readonly', true)
		})
	})



</script>



    <section>
    <article class="tabla-resultados tabla-no-centrada">
        <!-- <table class="tabla-resultados">
            <tr>
                <th class="campo-resultados">Nombre</th>
                <th class="campo-resultados">Credenciales</th>
                <th class="campo-resultados"></th>
                <th class="campo-resultados"></th>
                <th class="campo-resultados"></th>
            </tr> -->
            <table class="table table-striped">
                <thead class="thead-light">
                    <tr>
                        <th class="campo-resultados">Nombre</th>
                        <th class="campo-resultados">Credenciales</th>
                        <th class="campo-resultados"></th>
                        <th class="campo-resultados"></th>
                        <th class="campo-resultados"></th>
                    </tr>
                </thead>
                <tbody>    


    <?php

    if ($result->num_rows > 0) {
        while ($resultado = $result->fetch_assoc()) {
            ?>
            <tr onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
                <td class="campo-resultados"><?php echo $resultado['Nombre'];   ?></td>
                <td class="campo-resultados"><?php echo $resultado['ROL_Nombre'];   ?></td>
                <td class="campo-resultados">

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendMailModal" data-whatever="<?php echo $resultado['Email']?>">
                        <img src='imagenes/email.png' alt='icono de email' class='icono'>
                    </button>
                </td>
                <td class="campo-resultados">
                    <?php 
                    if(($resultado['ROL_IdRol']>$idrol)&&($resultado['eliminado']==0)){
                        echo    ' <button onclick="deleteUser(' . $resultado['IdUsuario'] . ')" class="btn btn-danger">X</button>';                        
                    }
                    ?>
                </td>
                <td class="campo-resultados">
                    <?php
                    if(($resultado['ROL_IdRol']>$idrol)&&($resultado['eliminado']==1)){
                        echo    ' <button onclick="restoreUser(' . $resultado['IdUsuario'] . ')" class="btn btn-secondary">Restaurar</button>'; 
                    }
                    ?>
                </td>
            </tr>
            <?php
        }?>
            </tbody>
            </table>
    <?php
    } else {
        echo "No se encontraron usuarios.";
    }
}
?>


