<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- <link href="https://fonts.googleapis.com/css2?family=Amarante&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="css/estilos.css">

<title>Formulario de Login</title>
</head>

<body>
	<header>
		<h1>Admin Kaizen</h1>
		<a href="./form_login.php"><img class="main-logo" src="./imagenes/logo.png" alt="adminkaizen logo"></img></a>
		
		<p class="isotipo">Admin Kaizen</p>
	</header>
	<h2>Loguearse</h2>
	<form class="login-form" action="login.php" method="post">
    	<label>
			<!-- Nombre de usuario -->
        	<input class="form-input" placeholder="Nombre de usuario" name="usuario" type="text" maxlength="12" required/>
        </label><br />
        <label>
			<!-- Contraseña -->
        	<input class="form-input" placeholder="Contraseña" type="password" name="password" maxlength="12" required/>
        </label><br />
        	<input class="form-button" type="submit" value="Login"/>	
    </form>
    <!-- <a href="form_registro.php">Registrate</a> si no sos usuario. -->
    


</body>
</html>