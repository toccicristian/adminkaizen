<?php session_start();?>
<!doctype html>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E2>
	<link rel="stylesheet" href="css/estilos.css">
	<title>Admin Kaizen - Mensajes</title>
	</head>
	<body>
<?php
		include("conexion.php");
		$idUsuario=$_SESSION['idusuario'];
		$consultaMensajes=mysqli_query($conexion,"SELECT M.idMensaje, M.asunto, M.cuerpo, M.leido, M.eliminado, M.fecha, U.Nombre AS remitente
                                                FROM `MENSAJES` M 
						JOIN USUARIO U ON M.idRemitente=U.IdUsuario
                                                WHERE M.idReceptor='$idUsuario' ORDER BY M.idMensaje DESC");
?>
		<header>
                	<h3 class='centrado sombreado'>MENSAJES</h3>
		</header>
		<article class='tabla-resultados'>
			<p>
				<table>
					<tr>
						<th class='campo-resultado'>Fecha</th>
						<th class='campo-resultados'>Remitente</th>
						<th class='campo-resultados'>Asunto</th>
						<th class='campo-resultados'>Mensaje</th>
					</tr>
<?php
					while($msgArr=mysqli_fetch_array($consultaMensajes)){
?>
					<tr>
						<?php 
							$nuevo="";
							if($msgArr['leido']==0){
								$nuevo="***";
							}
						?>
                                                <td><?php echo $nuevo.$msgArr['fecha'].$nuevo?></td>
                                                <td><?php echo $msgArr['remitente']?></td>
                                                <td><?php echo $msgArr['asunto']?></td>
                                                <td><?php echo $msgArr['cuerpo']?></td>

                                                <?php
							$idMsg=$msgArr['idMensaje'];
	                                                mysqli_query($conexion,"UPDATE MENSAJES SET MENSAJES.leido=1 WHERE MENSAJES.idMensaje='$idMsg'");
                                                ?>

                                        </tr>

<?php
					}
?>
				</table>
			</p>
		</article>

	</body>
</html>
