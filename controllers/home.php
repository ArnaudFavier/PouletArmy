<?php

$title = 'Accueil';

require_once('models/user.php');
require_once('utils/security.php');

if(!isset($_SESSION['user'])) {
	Security::createSecurityCookie();

	require_once('views/home.php');
} else {
	require_once('controllers/game.php');
}