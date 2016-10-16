<?php

$title = 'Accueil';

require_once('models/user.php');
require_once('utils/tools.php');
require_once('utils/security.php');

if(!isset($_SESSION['user'])) {
	Security::createSecurityCookie();

	/*
	 |------------------------------
	 | Affectation des variables d'affichage
	 |------------------------------
	 */

	$pseudoLastPlayer = Tools::getPseudoLastPlayerCookie();

	$view = array(
		'pseudoLastPlayer' => $pseudoLastPlayer
	);

	require_once('views/home.php');
} else {
	require_once('controllers/game.php');
}