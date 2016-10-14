<?php

$title = 'Graines';

require('connected.php'); // anciennement require_once() mais remplacé par require() car permet de recharger les ressources à afficher après une amélioration de batiment
require_once('params/rules.php');
require_once('utils/tools.php');

/*
 |------------------------------
 | Calculs des variables d'affichage
 |------------------------------
 */

/* --- Champs --- */
/* Actuellement */
$champsNiveau = $_SESSION['ressource']['champs']; // Niveau des champs ~
$graineHeure = floor(Rules::graineSeconde($_SESSION['ressource']['champs']) * 3600); // Production de graine par heure

/* Amélioration */
$champsProchainNiveau = Rules::prochainNiveauChamps($_SESSION['ressource']['champs']); // Le prochain niveau possible des champs
$champsGraineAugmentation = floor(Rules::graineSeconde($champsProchainNiveau) * 3600); // L'augmentation de graine avec le prochain niveau des champs (en graine par heure)
$champsGraineAugmentationDifference = $champsGraineAugmentation - $graineHeure; // Le gain de production avec un niveau supplémentaire
$champsCout = Rules::coutChamps($champsProchainNiveau); // Le cout du prochain niveau des champs

/* --- Entrepôt --- */
/* Actuellement */
$entrepotNiveau = $_SESSION['ressource']['entrepot']; // Le niveau du entrepôt
$graineMax = floor(Rules::graineMaximum($_SESSION['ressource']['entrepot'])); // Le graine maximum que peut contenir le entrepôt

/* Amélioration */
$entrepotProchainNiveau = Rules::prochainNiveauEntrepot($_SESSION['ressource']['entrepot']); // Le prochain niveau possible
$entrepotGraineAugmentation = floor(Rules::graineMaximum($entrepotProchainNiveau)); // L'augmentation du stockage de graine avec le prochain niveau (en graine)
$entrepotGraineAugmentationDifference = $entrepotGraineAugmentation - $graineMax; // Le gain de stockage avec un niveau supplémentaire
$entrepotCout = Rules::coutEntrepot($entrepotProchainNiveau); // Le cout du prochain niveau

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	/* --- Champs --- */
	/* Actuellement */
	'champsNiveau' => $champsNiveau,
	'graineHeure' => Tools::formatNumber($graineHeure),

	/* Amélioration */
	'champsProchainNiveau' => $champsProchainNiveau,
	'champsGraineAugmentation' => Tools::formatNumber($champsGraineAugmentation),
	'champsGraineAugmentationDifference' => Tools::formatNumber($champsGraineAugmentationDifference),
	'champsCout' => Tools::formatNumber($champsCout),

	/* --- Entrepôt --- */
	/* Actuellement */
	'entrepotNiveau' => $entrepotNiveau,
	'graineMax' => Tools::formatNumber($graineMax),

	/* Amélioration */
	'entrepotProchainNiveau' => $entrepotProchainNiveau,
	'entrepotGraineAugmentation' => Tools::formatNumber($entrepotGraineAugmentation),
	'entrepotGraineAugmentationDifference' => Tools::formatNumber($entrepotGraineAugmentationDifference),
	'entrepotCout' => Tools::formatNumber($entrepotCout),
);

require_once('views/game/graine.php');