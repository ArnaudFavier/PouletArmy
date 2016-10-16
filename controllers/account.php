<?php

$title = 'Mon compte';

require_once('connected.php');
require_once('models/user.php');

/* Informations joueur */
// Récupération des informations sur le joueur courrant
$user = User::getInformations(unserialize($_SESSION['user'])->id);

// Calcul du nombre de jours depuis son inscription
$dateInscription = $user['inscription'];
$dateActuelle = time();
$nombreJourInscription = abs($dateActuelle - $dateInscription);
$nombreJourInscription = floor($nombreJourInscription / (60 * 60 * 24));

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */
$view = array(
	/* Information du joueur */
	'pseudo' => $user['pseudo'],
	'nombreJourInscription' => $nombreJourInscription
);

require_once('views/game/account.php');