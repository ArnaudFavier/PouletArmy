<?php

require_once('controllers/connected.php');
require_once('utils/tools.php');

/*
 |------------------------------
 | Traitement des données issues du formulaire
 |------------------------------
 */

$listePoulets = Poulet::getAllWithArmy(unserialize($_SESSION['user'])->id);
$coutTotal = 0;

foreach ($listePoulets as $key => $value) {
	/* Champs POST existants */
	if (!isset($_POST[$listePoulets[$key]['id']])) {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Recrutement impossible.', 'poulailler');
	}

	/* Champs POST valide */
	$nombre = Tools::cleanInput($_POST[$listePoulets[$key]['id']]);
	// Si le champs est vide, on met 0 comme nombre souhaité, sinon ensemble de vérifications
	if (empty($nombre)) {
		$nombre = 0;
	} else {
		// Vérification du type de la variable $nombre
		if (!is_numeric($nombre)) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Recrutement invalide.', 'poulailler');
		}

		// Détection si c'est un nombre décimal
		$nombreFloat = (float) $nombre;
		// Si le cast a réussi et que le nombre est différent d'un entier
		if ($nombreFloat && $nombreFloat != (int) $nombreFloat) {
			// $nombre est décimal
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Recrutement invalide :<br>fragment de poulet non autorisé.', 'poulailler');
		}

		$nombre = (int) $nombre; // Le type de nombre est par défaut une string
		// Vérification que ce soit un entier positif
		if (!is_int($nombre) || $nombre < 0) {
			Tools::message(Tools::TYPE_MESSAGE['Error'], 'Recrutement invalide.', 'poulailler');
		}
	}

	$listePoulets[$key]['nombre'] = $nombre;
	$coutTotal += $nombre * $listePoulets[$key]['cout'];

	// Regarde si le type de poulet existe dans la table army (via la nullité de quantité)
	// et met à jour le booléen d'insertion en conséquence
	// AMELIORATION : intégrer la sémantique de la portion de code dans le model Poulet
	if (is_null($listePoulets[$key]['quantite'])) {
		$listePoulets[$key]['quantite'] = 0;
		$listePoulets[$key]['debloque'] = $listePoulets[$key]['debut'];
		$listePoulets[$key]['insert'] = true;
	} else {
		$listePoulets[$key]['insert'] = false;
	}
}

/* Assez de graine pour payer le recrutement */
if ($coutTotal > $_SESSION['ressource']['graine']) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Recrutement annulé :<br>graines insuffisantes.', 'poulailler');
}

/*
 |------------------------------
 | Mise à jour en base de données
 |------------------------------
 */

// S'il n'y a pas de poulets à recruter
if ($coutTotal <= 0) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Aucun poulet à recruter.', 'poulailler');
}

// Pluriel
$nombreTotalPouletRecrute = 0;

// Pour chaque poulet : ajout de la quantité
foreach ($listePoulets as $poulet) {
	// S'il y a une quantité de poulets à ajouter et qu'ils sont débloqués
	if ($poulet['nombre'] > 0 && $poulet['debloque'] == true) {
		// Quantité totale : nombre de poulets actuellement possédé + nombre de poulet recruté en plus
		$quantiteTotale = $poulet['quantite'] + $poulet['nombre'];
		$nombreTotalPouletRecrute += $poulet['nombre'];
		// Déjà présent dans la table army -> UPDATE, sinon -> INSERT
		if ($poulet['insert'] == false) {
			Poulet::setQuantite(unserialize($_SESSION['user'])->id, $poulet['id'], $quantiteTotale);
		} else {
			// Va faire référence à l'attribut debut de Poulet pour l'attribut debloque de Army
			// voir plus haut : $listePoulets[$key]['debloque'] = $listePoulets[$key]['debut']
			Poulet::insertArmy(unserialize($_SESSION['user'])->id, $poulet, $quantiteTotale);
		}
	}
}

// Mise à jour des ressources (soustraction des graines dépensées)
$nouvelleRessource = array(
	'graine' => $_SESSION['ressource']['graine'] - $coutTotal,
);
Ressource::modify(unserialize($_SESSION['user'])->id, $nouvelleRessource);

// Pluriel
$plurielPoulet = '';
if ($nombreTotalPouletRecrute > 1) {
	$plurielPoulet = 's';
}

// Message de succès
Tools::message(Tools::TYPE_MESSAGE['Success'], 'Poulet' . $plurielPoulet . ' recruté' . $plurielPoulet .'.', 'poulailler');

header('Location: poulailler');
exit;