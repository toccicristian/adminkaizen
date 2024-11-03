<?php
    include ("conexion.php");
    
    if (isset($_POST['search'])) {
        $nombreTarea = $_POST['search'];
        $consulta = MySQLi_query($conexion, "SELECT Nombre FROM TAREA WHERE Nombre LIKE '%$nombreTarea%' LIMIT 10");
?>
    <ul>
<?php
    while ($resultado = MySQLi_fetch_array($consulta)) {
?>
        <li onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
            <a><?php echo $resultado['Nombre']; ?>
            </a>
      </li>
<?php
    }}
?>

    </ul>