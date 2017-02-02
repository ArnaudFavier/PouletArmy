<?php

$title = 'Accueil';

require_once('connected.php');
require_once('params/rules.php');
require_once('utils/tools.php');
require_once('models/poulet.php');

/*
 |------------------------------
 | Calculs des variables d'affichage
 |------------------------------
 */

/* Ressources par heure */
$boisHeure = floor(Rules::boisSeconde($_SESSION['ressource']['scierie']) * 3600);
$graineHeure = floor(Rules::graineSeconde($_SESSION['ressource']['champs']) * 3600);

/* Image du village en fonction du niveau des batiments construits */
if($_SESSION['ressource']['scierie'] >= 20 && $_SESSION['ressource']['champs'] >= 10 && $_SESSION['ressource']['comptoir'] >= 1) {
	$chateauNiveau = 3;
} elseif($_SESSION['ressource']['scierie'] >= 10 && $_SESSION['ressource']['champs'] >= 3) {
	$chateauNiveau = 2;
} elseif($_SESSION['ressource']['scierie'] >= 3) {
	$chateauNiveau = 1;
} else {
	$chateauNiveau = 0;
}

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */
$view = array(
	/* --- Ressources/heure --- */
	'boisHeure' => Tools::formatNumber($boisHeure),
	'graineHeure' => Tools::formatNumber($graineHeure),

	/* --- Image village --- */
	'chateauNiveau' => $chateauNiveau,

	/* --- Nombre de poulets --- */
	'nombrePoulets' => Poulet::countPoulet(unserialize($_SESSION['user'])->id),
);

require_once('views/game/game.php');