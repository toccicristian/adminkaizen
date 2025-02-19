<?php

	$dbhost = 'localhost';
	$dbuser = 'c2770751_db1';
	$dbpass = 'susuwoKE73';
	$dbname = 'c2770751_db1';

	$conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Ocurrio un error al conectarse al servidor mysql');
	//mysql_select_db($dbname);
?>