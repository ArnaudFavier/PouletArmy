<?php

$title = 'Bois';

require('connected.php'); // anciennement require_once() mais remplacé par require() car permet de recharger les ressources à afficher après une amélioration de batiment
require_once('params/rules.php');
require_once('utils/tools.php');

/*
 |------------------------------
 | Calculs des variables d'affichage
 |------------------------------
 */

/* --- Scierie --- */
/* Actuellement */
$scierieNiveau = $_SESSION['ressource']['scierie']; // Niveau de la scierie ~
$boisHeure = floor(Rules::boisSeconde($_SESSION['ressource']['scierie']) * 3600); // Production de bois par heure

/* Amélioration */
$scierieProchainNiveau = Rules::prochainNiveauScierie($_SESSION['ressource']['scierie']); // Le prochain niveau possible de la scierie
$scierieBoisAugmentation = floor(Rules::boisSeconde($scierieProchainNiveau) * 3600); // L'augmentation de bois avec le prochain niveau de la scierie (en bois par heure)
$scierieBoisAugmentationDifference = $scierieBoisAugmentation - $boisHeure; // Le gain de production avec un niveau supplémentaire
$scierieCout = Rules::coutScierie($scierieProchainNiveau); // Le cout du prochain niveau de la scierie

/* --- Depôt --- */
/* Actuellement */
$depotNiveau = $_SESSION['ressource']['depot']; // Le niveau du depot
$boisMax = floor(Rules::boisMaximum($_SESSION['ressource']['depot'])); // Le bois maximum que peut contenir le depot

/* Amélioration */
$depotProchainNiveau = Rules::prochainNiveauDepot($_SESSION['ressource']['depot']); // Le prochain niveau possible
$depotBoisAugmentation = floor(Rules::boisMaximum($depotProchainNiveau)); // L'augmentation du stockage de bois avec le prochain niveau (en bois)
$depotBoisAugmentationDifference = $depotBoisAugmentation - $boisMax; // Le gain de stockage avec un niveau supplémentaire
$depotCout = Rules::coutDepot($depotProchainNiveau); // Le cout du prochain niveau

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	/* --- Scierie --- */
	/* Actuellement */
	'scierieNiveau' => $scierieNiveau,
	'boisHeure' => Tools::formatNumber($boisHeure),

	/* Amélioration */
	'scierieProchainNiveau' => $scierieProchainNiveau,
	'scierieBoisAugmentation' => Tools::formatNumber($scierieBoisAugmentation),
	'scierieBoisAugmentationDifference' => Tools::formatNumber($scierieBoisAugmentationDifference),
	'scierieCout' => Tools::formatNumber($scierieCout),

	/* --- Depôt --- */
	/* Actuellement */
	'depotNiveau' => $depotNiveau,
	'boisMax' => Tools::formatNumber($boisMax),

	/* Amélioration */
	'depotProchainNiveau' => $depotProchainNiveau,
	'depotBoisAugmentation' => Tools::formatNumber($depotBoisAugmentation),
	'depotBoisAugmentationDifference' => Tools::formatNumber($depotBoisAugmentationDifference),
	'depotCout' => Tools::formatNumber($depotCout),
);

require_once('views/game/bois.php');