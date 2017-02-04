<?php

$title = 'Poulailler';

require_once('connected.php');
require_once('models/poulet.php');
require_once('params/rules.php');
require_once('utils/tools.php');

/*
 |------------------------------
 | Calculs des variables d'affichage
 |------------------------------
 */
$listePoulets = array();
$poulailler = array();
$poulailler['niveau'] = Poulet::getPoulailler(unserialize($_SESSION['user'])->id);

// Si le poulailler a été construit
if ($poulailler['niveau'] == 0) {
	$poulailler['cout'] = Tools::formatNumber(Rules::COUT_POULAILLER_CONSTRUCTION);
} else {
	/* Récupération de la liste des poulets avec les quantités possédées correspondantes */
	$listePoulets = Poulet::getAllWithArmy(unserialize($_SESSION['user'])->id);

	/* Mise à jour des informations de la liste des poulets */
	foreach ($listePoulets as $key => $value) {
		// 0 si pas de quantité possédée
		if (is_null($listePoulets[$key]['quantite'])) {
			$listePoulets[$key]['quantite'] = 0;
		}
		// Attribut par defaut du poulet s'il n'est pas encore débloqué (souvent false)
		if (is_null($listePoulets[$key]['debloque'])) {
			$listePoulets[$key]['debloque'] = $listePoulets[$key]['debut'];
		}
		// Si le poulet est bloqué, on ne l'affiche pas (retrait de la liste des poulets à afficher)
		if ($listePoulets[$key]['debloque'] == false) {
			unset($listePoulets[$key]);
			continue;
		}
		// Nombre maximum de poulet recrutable
		$listePoulets[$key]['maximum'] = intval($_SESSION['ressource']['graine'] / $listePoulets[$key]['cout']);
		// Désactivation du champs nombre si poulet non débloqué ou pas assez de graine pour recruter
		if ($listePoulets[$key]['debloque'] == false || $listePoulets[$key]['maximum'] == 0) {
			$listePoulets[$key]['disable'] = 'readonly';
		} else {
			$listePoulets[$key]['disable'] = '';
		}
	}
}

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */
$view = array(
	'poulailler' => $poulailler,
	'poulets' => $listePoulets,
);

require_once('views/game/poulailler.php');