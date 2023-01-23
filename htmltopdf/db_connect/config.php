<?php
	//Style Orienté Objet  :AU
	// Les credentials de la base
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'htmltopdfhistory');

	// Essaie de connexion sur la base MySQL.
	$mysql_db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if (!$mysql_db) 
	{
		die("Error: Unable to connect " . $mysql_db->connect_error);
	}

	// Style Procedural ;API
	$server = "localhost";
	$username = "root";
	$password = "root";
	$db = "htmltopdfhistory";
	$conn = mysqli_connect($server, $username, $password, $db);