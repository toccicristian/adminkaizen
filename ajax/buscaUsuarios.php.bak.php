<?php session_start(); ?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript" src="./js/busqueda.js"></script>


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="css/estilos.css">
    </head>



<body>
    
<?php
    include ("../conexion.php");
    if (isset($_POST['search'])) {
        $nombreUsuario = $_POST['search'];
        $consulta = MySQLi_query($conexion, "SELECT U.IdUsuario, U.Nombre, U.Email, U.ROL_IdRol, R.Nombre as ROL_Nombre, U.eliminado
                                            FROM USUARIO U
                                            JOIN ROL R
                                            ON U.ROL_IdRol = R.IdRol
                                            WHERE U.Nombre LIKE '%$nombreUsuario%' LIMIT 20");

        $idrol=$_SESSION['idrol'];
        $username=$_SESSION['nombre'];
}      
?>

<div id="userList"></div>

<script>

        function loadUsers() {
            $.ajax({
                url: 'fetchUsers.php', 
                type: 'GET',
                success: function(data) {
                    $('#userList').html(data); 
                },
                error: function() {
                    $('#userList').html('Error al cargar los usuarios.'); 
                }
            });
        }

        function deleteUser(id) {
            if (confirm("Está seguro de que desea eliminar este usuario?")){
                $.ajax({
                url: 'baja.php', 
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    alert(response); 
                    loadUsers(); 
                },
                error: function() {
                    alert('Error al eliminar el usuario.'); 
                }
            });                
            }
            else{
                alert("El usuario no ha sido eliminado.");
            }

        }

    </script>

</body>
</html>