<?php session_start(); ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">

<?php
    include ("conexion.php");
    
    if (isset($_POST['search'])) {
        $nombreTarea = $_POST['search'];
        $consulta = MySQLi_query($conexion, "SELECT idTarea, Nombre, Inicio, MejorFin, PeorFin, OwnerId, eliminada
                                            FROM TAREA WHERE Nombre LIKE '%$nombreTarea%' LIMIT 10");

        $idrol=$_SESSION['idrol'];
        $username=$_SESSION['nombre'];
        
?>
<section>
    <article class="tabla-resultados">
        <table class="tabla-resultados">
            <tr>
                <th class="campo-resultados">Nombre</th>
                <th class="campo-resultados">Inicio</th>
                <th class="campo-resultados">Mejor Fin</th>
                <th class="campo-resultados">Peor Fin</th>
            </tr>
<?php
    while ($resultado = MySQLi_fetch_array($consulta)) {
        if(($resultado['eliminada']==0) || ($_POST['mostrareliminadas']!='false')){
            
        
?>
            <tr onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
                <td class="campo-resultados"><?php echo $resultado['Nombre'];   ?></td>
                <td class="campo-resultados"><?php echo $resultado['Inicio'];   ?></td>
                <td class="campo-resultados"><?php echo $resultado['MejorFin']; ?></td>
                <td class="campo-resultados"><?php echo $resultado['PeorFin'];  ?></td>
<?php
        $ownerid=$resultado['OwnerId'];
        $consultaEsOwner=mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE USUARIO.Nombre = '$username' AND USUARIO.IdUsuario = '$ownerid'");
        $consultaOutRol=mysqli_query($conexion, "SELECT IdUsuario FROM USUARIO WHERE USUARIO.IdUsuario = '$ownerid' AND USUARIO.ROL_IdRol > '$idrol'");
        if((mysqli_num_rows($consultaEsOwner)!=0) || (mysqli_num_rows($consultaOutRol)!=0)){
            ?>  <td class="campo-resultados">
<?php 
            if($resultado['eliminada']==1){
?>
                    <form action="restaurartarea.php" method="post">
                        <input type="hidden" name="taskid" value=<?php echo $resultado['idTarea'] ?>>
                        <button type="submit" class="btn btn-secondary">Restaurar</button>
                    </form>
<?php
            }
            else{
?>
                    <form action="eliminartarea.php" method="post" >
                        <input type="hidden" name="taskid" value=<?php echo $resultado['idTarea'] ?>>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                <?php
            }
            ?>
            </td>
        <?php
        }
        

?>
            </tr>
<?php
    }}}
?>
        </table>
    </article>
</section>
