<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'adminkaizen';

	$conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Ocurrio un error al conectarse al servidor mysql');
?>
