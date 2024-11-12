<?php session_start();?>

<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

<section class="formulario-edicion">
<?php
    $consulta=mysqli_query($conexion,"SELECT Nombre, Notas FROM TAREA WHERE TAREA.idTarea='$idTarea'");
    $tarea=mysqli_fetch_array($consulta);
    mysqli_free_result($consulta);
?>
    <h3>EDICION DE LA TAREA:</h3> 
		<p><?php echo $tarea['Nombre'] ?></p>
        <p>
            Usuarios Asignados: 
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
            echo $usuariosAsignados;
            mysqli_free_result($consulta);
?>
        </p>
		<p>
<?php 
			if(isset($_SESSION['mensajesistema'])){
				echo "-".$_SESSION['mensajesistema'];
				unset($_SESSION['mensajesistema']);
			}
?>
		</p>

    <article>
        <!-- <form action="guardarCambiosEnTarea.php" class="formulario-edicion"> -->
        <form id="formNotas">
            <p><textarea maxlength=255 rows="5" cols="40" placeholder="Notas adicionales..." name="tasknotes" id="tasknotes"><?php echo $tarea['Notas']; ?></textarea></p>
            <input type="hidden" name="taskid" id="taskid" value="<?php echo $idTarea; ?>">
            <button type="submit" class="btn btn-success" >Guardar Cambios</button>      
        </form>
    </article>

    <article>
        <h3>Subir un Documento</h3>
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" id="file" required>
            <input type="text" name="detalle" id="detalle" placeholder="Detalle" required>
            <input type="hidden" name="tarea_id" id="tarea_id" value="<?php echo $idTarea ?>">
            <input type="submit" value="Subir Documento">
        </form>
        <div id="response"></div>
        <div class="formulario-edicion">
            <h3>Documentos Subidos</h3>
            <div id="documentList"></div>
        </div>
        
    </article>

</section>



<script>
        $(document).ready(function() {

            loadDocuments();

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); 

                var formData = new FormData(this); 

                $.ajax({
                    url: 'upload.php', 
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
        });

        function loadDocuments() {
            $.ajax({
                url: 'fetch_documents.php', 
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
                url: 'delete_document.php', 
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

    </body>
</html>