<?php

$title = 'Mission rapport';

require_once('controllers/connected.php');
require_once('utils/tools.php');
require_once('models/mission.php');

/*
 |------------------------------
 | Traitement des données issues du formulaire
 |------------------------------
 */

/* --- Mission --- */

/* Champs POST existant */
if (!isset($_POST['mission'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission incorrecte.', 'mission');
}

/* Champs POST non vide */
$missionId = Tools::cleanInput($_POST['mission']);
if (empty($missionId)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission invalide.', 'mission');
}

/* Mission existante */
if (isset(Mission::MISSION[$missionId]) && $missionId == Mission::MISSION[$missionId]['id']) {
	$mission = Mission::MISSION[$missionId];
} else {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission inconnue.', 'mission');
}

/* --- Poulets --- */

$poulets = Poulet::getArmyWithPoulet(unserialize($_SESSION['user'])->id);
$nombreTotal = 0;

foreach ($poulets as $key => $value) {
	/* Champs POST existants */
	if (!isset($_POST[$poulets[$key]['id']])) {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission invalide.', 'mission');
	}

	/* Champs POST valide */
	$nombre = Tools::cleanInput($_POST[$poulets[$key]['id']]);
	// Si le champs est vide, on met 0 comme nombre souhaité, sinon ensemble de vérifications
	if (empty($nombre)) {
		$nombre = 0;
	} else {
		// Vérification du type de la variable $nombre
		if (!is_numeric($nombre)) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission invalide.', 'mission');
		}

		// Détection si c'est un nombre décimal
		$nombreFloat = (float) $nombre;
		// Si le cast a réussi et que le nombre est différent d'un entier
		if ($nombreFloat && $nombreFloat != (int) $nombreFloat) {
			// $nombre est décimal
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission impossible :<br>fragments de poulet non autorisés.', 'mission');
		}

		$nombre = (int) $nombre; // Le type de nombre est par défaut une string
		// Vérification que ce soit un entier positif
		if (!is_int($nombre) || $nombre < 0) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission impossible.', 'mission');
		}
	}

	if ($nombre > $poulets[$key]['quantite']) {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission impossible :<br>trop de poulets demandés.', 'mission');
	}

	$poulets[$key]['nombre'] = $nombre;
	$nombreTotal += $nombre;
}

/*
 |------------------------------
 | Traitement intrinsèque
 |------------------------------
 */

/* S'il y a bien des poulets à envoyer en mission */
if ($nombreTotal <= 0) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission impossible :<br>pas de poulet à envoyer.', 'mission');
}

/* S'il y a au moins 2 poulets à envoyer en mission (pour le pluriel des textes de missions) */
if ($nombreTotal < 2) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission impossible :<br>nécessite 2 poulets au minimum.', 'mission');
}

/* Lancement de la mission */
$rapport = Mission::lancerMission($mission, $poulets);

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'mission' => $mission,
	'rapport' => $rapport,
);

require_once('views/game/mission-rapport.php');