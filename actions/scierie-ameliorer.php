<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('utils/tools.php');
require_once('models/ressource.php');

if($_SESSION['ressource']['scierie'] + 1 == Rules::prochainNiveauScierie($_SESSION['ressource']['scierie'])) { // Augmentation possible ? (si +1 niveau ne dépasse pas la limite)

	if($_SESSION['ressource']['bois'] >= Rules::coutScierie(Rules::prochainNiveauScierie($_SESSION['ressource']['scierie']))) { // Ressources suffisantes pour le prochain niveau ?
		// Tableau contenant les nouvelles valeurs des ressources
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::coutScierie(Rules::prochainNiveauScierie($_SESSION['ressource']['scierie'])),
			'scierie' => Rules::prochainNiveauScierie($_SESSION['ressource']['scierie'])
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);

		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Niveau de la scierie augmenté.', 'bois');
	} else { // Erreur : Ressources insuffisantes
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'bois');
	}
} else { // Erreur : le niveau de la scierie dépasse le niveau maximum autorisé
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Niveau maximum de la scierie atteint.', 'bois');
}

header('Location: bois'); // header() pour recharger les quantités des ressources à afficher
exit;