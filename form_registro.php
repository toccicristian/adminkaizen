<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Kaizen - Registro de Usuarios</title>
</head>

<body>
	<h2>Regístrese en el sitio</h2>
    <form action="registro.php" method="post" >
    	<label>
            <!-- Nombre -->
        	<input class="form-input" type="text" placeholder="Nombre" name="nombre" required />
        </label><br />
		<label>
            <!-- Apellido -->
        	<input class="form-input" type="text" placeholder="Apellido" name="apellido" required />
        </label><br />
        <label>
            <!-- Email -->
        	<input class="form-input" type="email" placeholder="Correo" name="email" required />
        </label><br />
        <label>
            <!-- Nombre de usuario -->
        	<input class="form-input" name="usuario" placeholder="Nombre de Usuario" type="text" maxlength="12" />
        </label><br />
        <label>
            <!-- Contraseña -->
        	<input class="form-input" type="password" placeholder="Contraseña" name="password" maxlength="12" />
        </label><br />
        <label>
        <label>
            <p class="centrado">Nivel</p>
            
            <select class="form-input" name="nivel">
                <?php 
                    include("conexion.php");
                    $consulta=mysqli_query($conexion, "SELECT IdNivel, Nombre FROM niveles");       
                    $resultado=mysqli_num_rows($consulta);
                    while($resultados=mysqli_fetch_array($consulta)) {
                        ?><option <?php if ($resultados['IdNivel']==5){echo "selected";}?>><?php echo $resultados['IdNivel']." - ".$resultados['Nombre']?></option><?php
                    }
                ?>
            </select>
        </label><br/>
        <!-- <label><input class="form-input" name="newsletter" type="checkbox" value="si" checked="checked" /> Sí, deseo recibir informacion por mail.</label><br /> -->
        <input class="form-button" type="submit" value="Registrarse"/>	
    </form>

</body>
</html>