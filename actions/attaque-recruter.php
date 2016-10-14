<?php

$title = 'Attaque recrutement';

require_once('controllers/connected.php');
require_once('utils/tools.php');
require_once('models/poulet.php');

/*
 |------------------------------
 | Traitement des données issues du formulaire
 |------------------------------
 */

/* Champs POST existant */
if(!isset($_POST['joueur'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Joueur incorrect.', 'attaque');
}

/* Champs POST non vide */
$joueurId = Tools::cleanInput($_POST['joueur']);
if(empty($joueurId)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Joueur invalide.', 'attaque');
}

/* Joueur différent de l'attaquant */
if($joueurId == unserialize($_SESSION['user'])->id) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Joueur identique.', 'attaque');
}

/* Joueur existant */
if(User::existId($joueurId) == false) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Joueur inconnu.', 'attaque');
}

/*
 |------------------------------
 | Traitement intrinsèque
 |------------------------------
 */

/* Récupération des poulets */
$armee = Poulet::getAllWithArmy(unserialize($_SESSION['user'])->id);

// S'il n'y en a qu'un seul, on le met dans un tableau, car l'affichage gère des tableaux de plusieurs poulets
if(count($armee) == 1) {
	$armee = array($armee);
}

// S'il n'y a pas de poulets (quantité = 0), alors champs désactivés
foreach ($armee as $key => $value) {
	if(empty($armee[$key]['quantite'])) { // Null ou 0 donc force à 0
		$armee[$key]['quantite'] = 0;
		$armee[$key]['disable'] = 'readonly';
	} else {
		$armee[$key]['disable'] = '';
	}
}

// Récupération des informations du joueur
$joueur = User::getInformations($joueurId);

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'joueur' => $joueur,
	'poulets' => $armee,
);

require_once('views/game/attaque-recruter.php');