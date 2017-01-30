<?php

$title = 'Attaque rapport';

require_once('controllers/connected.php');
require_once('utils/tools.php');
require_once('models/attaque.php');
require_once('models/poulet.php');
require_once('models/rapport.php');

/*
 |------------------------------
 | Traitement des données issues du formulaire
 |------------------------------
 */

/* --- Joueur --- */

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

/* --- Poulets --- */

$poulets = Poulet::getArmyWithPoulet(unserialize($_SESSION['user'])->id);
$nombreTotal = 0;

foreach ($poulets as $key => $value) {
	/* Champs POST existants */
	if(!isset($_POST[$poulets[$key]['id']])) {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque invalide.', 'attaque');
	}

	/* Champs POST valide */
	$nombre = Tools::cleanInput($_POST[$poulets[$key]['id']]);
	// Si le champs est vide, on met 0 comme nombre souhaité, sinon ensemble de vérifications
	if(empty($nombre)) {
		$nombre = 0;
	} else {
		// Vérification du type de la variable $nombre
		if(!is_numeric($nombre)) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque invalide.', 'attaque');
		}

		// Détection si c'est un nombre décimal
		$nombreFloat = (float) $nombre;
		// Si le cast a réussi et que le nombre est différent d'un entier
		if($nombreFloat && $nombreFloat != (int) $nombreFloat)
		{
			// $nombre est décimal
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque impossible :<br>fragment de poulet non autorisé.', 'attaque');
		}

		$nombre = (int) $nombre; // Le type de nombre est par défaut une string donc cast en int
		// Vérification que ce soit un entier positif
		if(!is_int($nombre) || $nombre < 0) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque impossible.', 'attaque');
		}
	}

	if($nombre > $poulets[$key]['quantite']) {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque impossible :<br>trop de poulets demandés.', 'attaque');
	}

	$poulets[$key]['nombre'] = $nombre;
	$nombreTotal += $nombre;
}

/* S'il y a bien des poulets à envoyer au combat  */
if($nombreTotal <= 0) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Attaque impossible :<br>pas de poulets à envoyer au combat.', 'attaque');
}

/*
 |------------------------------
 | Traitement intrinsèque
 |------------------------------
 */

/* --- Attaque --- */

/* Récupération des informations du défenseur avant le combat */
$joueur = User::getInformations($joueurId);
$pouletsDefenseurDebut = Poulet::getAllWithArmy($joueurId);
$nombrePouletsDefenseurDebut = 0;
foreach ($pouletsDefenseurDebut as $poulet) {
	if (!empty($poulet['quantite'])) {
		$nombrePouletsDefenseurDebut += $poulet['quantite'];
	}
}

/* Lancement du combat */
$rapport = Attaque::lancerAttaque(unserialize($_SESSION['user'])->id, $joueurId, $poulets);

/* Récupération des informations du défenseur après le combat */
$pouletsDefenseurFin = Poulet::getAllWithArmy($joueurId);
$nombrePouletsDefenseurFin = 0;
foreach ($pouletsDefenseurFin as $poulet) {
	if (!empty($poulet['quantite'])) {
		$nombrePouletsDefenseurFin += $poulet['quantite'];
	}
}

/* Insertion du rapport en BDD pour le défenseur */
// TODO : récupérer la date du rapport en la générant en timestamp (1997), et non en date et heure séparée
Rapport::insert(time(), $joueurId, unserialize($_SESSION['user'])->id, $nombreTotal, $nombrePouletsDefenseurDebut - $nombrePouletsDefenseurFin, $rapport['boisPille'], $rapport['grainePille']);

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'joueur' => $joueur,
	'rapport' => $rapport,

	'listePoulets' => Poulet::getAll(), // Liste de tous les poulets
	'pouletsAttaquant' => $poulets,

	'pouletsDefenseurDebut' => $pouletsDefenseurDebut,
	'nombrePouletsDefenseurDebut' => $nombrePouletsDefenseurDebut,
	'pouletsDefenseurFin' => $pouletsDefenseurFin,
	'nombrePouletsDefenseurFin' => $nombrePouletsDefenseurFin,
);

require_once('views/game/attaque-rapport.php');