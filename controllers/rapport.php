<?php

$title = 'Rapports'; // Attention, utilisé dans le controlleur Connected

require_once('connected.php');
require_once('params/rules.php');
require_once('models/rapport.php');
require_once('models/user.php');

// Récupération des rapports bruts
$rapports = Rapport::getAll(unserialize($_SESSION['user'])->id);

// Formattage pour l'affichage
$listeRapports = array();
if (!empty($rapports)) {
	foreach ($rapports as $rapport) {
		$rapportFormate = array();

		$rapportFormate['dateAttaque'] = date('j/m/Y', $rapport['dateAttaque']);
		$rapportFormate['heureAttaque'] = date('H:i', $rapport['dateAttaque']);
		$rapportFormate['pseudoAttaquant'] = User::getInformations($rapport['idAttaquant'])['pseudo'];
		$rapportFormate['nbPouletAttaquant'] = $rapport['nbPouletAttaquant'];
		$rapportFormate['nbPouletDefenseurPerdu'] = $rapport['nbPouletDefenseurPerdu'];

		array_push($listeRapports, $rapportFormate);
	}
}

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'nombreRapportMax' => Rules::NB_RAPPORT,

	'listeRapports' => $listeRapports,
);

require_once('views/game/rapport.php');