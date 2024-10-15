<?php session_start();?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Resultados de Búsqueda</title>
</head>

<body>
<section>
	<?php
		include('conexion.php');

		$usuario = $_POST['usuario'];
		$consulta=mysqli_query($conexion, 
			"SELECT USUARIO.Nombre, USUARIO.EMail, ROL.Nombre Permiso FROM USUARIO
	INNER JOIN ROL ON USUARIO.ROL_IdRol = ROL.IdRol
    WHERE USUARIO.Nombre LIKE '%$usuario%'
    ORDER BY USUARIO.Nombre");
	?>
	
	<?php
		if(mysqli_num_rows($consulta)!=0){
			echo "Mostrando ".mysqli_num_rows($consulta)." resultados para: <em>".$usuario."</em><br />";
			?><article style="width:60%;margin:0 auto;border:solid;padding:10px"><?php
			while($resultado=mysqli_fetch_array($consulta)){
				?><p><?php
				echo $resultado['Nombre'] . " --> ";
				echo "<a href='mailto:".$resultado['EMail']."'>".$resultado['EMail']."</a> ";
				echo $resultado['Permiso']."<br />";
				?></p><?php
			}
			mysqli_free_result($consulta);
			mysqli_close($conexion);
		}else{
			echo "No se han encontrado resultados para <em>".$usuario."</em><br />";
		}
	?>
	</article>
</section>

</body>
</html>




