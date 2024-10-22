<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cerrando Sesión...</title>
</head>

<body>

<?php

	$_SESSION = array();
	session_destroy();

	header("Location:./index.php");
	exit();
	
?>	
    

</body>
</html>