<?php 
include("../conexion.php");


define('tableName', 'users');
$userData = $_POST;
loginUser($conexion, $userData);


function loginUser($conexion, $userData) {
    $usuario   = $userData['usuario'];
    $clave     = md5($userData['clave']);
   
    if(!empty($usuario) && !empty($clave)){
        
        $query = "SELECT Nombre, Clave FROM USUARIO WHERE Nombre = '$usuario' AND Clave = '$clave'";
        $resultado = $conexion->query($query);
        if($resultado->num_rows > 0) {
           session_start();
           $_SESSION['usuario'] = $usuario;
           echo "exito";
        } else {
            echo "Usuario o Clave incorrectos";
        }
     
   } else {
      echo "Al menos un campo se encuentra incompleto.";
   }
}
?>