<?php

$title = 'Attaque';

require_once('connected.php');
require_once('models/user.php');

$listeJoueurs = User::getListeJoueursWithoutId(unserialize($_SESSION['user'])->id);

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'listeJoueurs' => $listeJoueurs,
);

require_once('views/game/attaque.php');