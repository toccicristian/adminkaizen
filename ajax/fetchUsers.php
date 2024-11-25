<?php session_start();
include('../conexion.php');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

<section>
    <article class="tabla-resultados tabla-no-centrada">
        <!-- <table class="tabla-resultados"> -->
            <!-- <tr>
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
    $consulta=mysqli_query($conexion,"SELECT U.IdUsuario, U.Nombre, U.EMail, U.eliminado, U.ROL_IdRol, R.Nombre as ROL_Nombre
                                    FROM USUARIO U
                                    JOIN ROL R ON U.ROL_IdRol=R.IdRol");
    while ($resultado = MySQLi_fetch_array($consulta)) {
        $mostrarEliminados = isset($_POST['mostrareliminados']) ? $_POST['mostrareliminados'] : 'false';
        if(($resultado['eliminado']==0) || ($_POST['mostrareliminados']!='false')){
            
        
?>
            <tr onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
                <td class="campo-resultados"><?php echo $resultado['Nombre'];   ?></td>
                <td class="campo-resultados"><?php echo $resultado['ROL_Nombre'];   ?></td>
                <td class="campo-resultados">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendMailModal" data-whatever="<?php echo $resultado['EMail']?>">
                        <img src='imagenes/email.png' alt='icono de email' class='icono'>
                    </button>
                </td>
                <td class="campo-resultados">
                    <?php 
                    $idrol=$_SESSION['idrol'];
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
    }}
?>
            </tbody>


        </table>
    </article>
</section>