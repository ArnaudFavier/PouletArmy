<?php

$title = 'Déconnexion';

require_once('utils/tools.php');

$afficherMessageDeconnexion = false; // Pas de message si déjà déconnecté

if (isset($_SESSION['user'])) {
	unset($_SESSION['user']);
	$afficherMessageDeconnexion = true;
}

session_unset();

session_destroy();
session_start(); // Nouvelle session directement pour l'affichage du message de déconnexion

if ($afficherMessageDeconnexion) {
	Tools::message(Tools::TYPE_MESSAGE['Information'], 'Déconnexion effectuée.<br>A bientôt !');
} else {
	require_once('views/home.php');
}