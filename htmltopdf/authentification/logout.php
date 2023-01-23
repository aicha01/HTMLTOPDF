<?php
	// Démarrage des sessions.
	session_start();

	$_SESSION  = array();

	session_unset();
	// Détruite tous les sessions de l'utilisateur.
	session_destroy();

	// Rediriger ves la page d'accueil (page de login).
	header('location: /');
	exit;
?>