<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('utils/tools.php');
require_once('models/ressource.php');

if($_SESSION['ressource']['champs'] + 1 == Rules::prochainNiveauChamps($_SESSION['ressource']['champs'])) { // Augmentation possible ? (si +1 niveau ne dépasse pas la limite)

	if($_SESSION['ressource']['bois'] >= Rules::coutChamps(Rules::prochainNiveauChamps($_SESSION['ressource']['champs']))) { // Ressources suffisantes pour le prochain niveau ?
		// Tableau contenant les nouvelles valeurs des ressources
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::coutChamps(Rules::prochainNiveauChamps($_SESSION['ressource']['champs'])),
			'champs' => Rules::prochainNiveauChamps($_SESSION['ressource']['champs'])
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);

		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Niveau des champs augmenté.', 'graine');
	} else { // Erreur : Ressources insuffisantes
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'graine');
	}
} else { // Erreur : le niveau des champs dépasse le niveau maximum autorisé
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Niveau maximum des champs atteint.', 'graine');
}

header('Location: graine'); // header() pour recharger les quantités des ressources à afficher
exit;