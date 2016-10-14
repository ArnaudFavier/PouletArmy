<?php

$title = 'Mission recrutement';

require_once('controllers/connected.php');
require_once('utils/tools.php');
require_once('models/mission.php');
require_once('models/poulet.php');

/*
 |------------------------------
 | Traitement des données issues du formulaire
 |------------------------------
 */

/* Champs POST existant */
if(!isset($_POST['mission'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission incorrecte.', 'mission');
}

/* Champs POST non vide */
$missionId = Tools::cleanInput($_POST['mission']);
if(empty($missionId)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission invalide.', 'mission');
}

/*
 |------------------------------
 | Traitement intrinsèque
 |------------------------------
 */

/* Mission existante */
if(isset(Mission::MISSION[$missionId]) && $missionId == Mission::MISSION[$missionId]['id']) {
	$mission = Mission::MISSION[$missionId];
} else {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mission inconnue.', 'mission');
}

/* Récupération des poulets */
$armee = Poulet::getArmyWithPoulet(unserialize($_SESSION['user'])->id);

// S'il n'y en a qu'un seul, on le met dans un tableau, car l'affichage (la vue) gère des tableaux de plusieurs poulets
if(count($armee) == 1) {
	$armee = array($armee);
}

// S'il n'y a pas de poulets (quantité = 0), alors champs désactivé
foreach ($armee as $key => $value) {
	if($armee[$key]['quantite'] == 0) {
		$armee[$key]['disable'] = 'readonly';
	} else {
		$armee[$key]['disable'] = '';
	}
}

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'mission' => $mission,
	'poulets' => $armee,
);

require_once('views/game/mission-recruter.php');