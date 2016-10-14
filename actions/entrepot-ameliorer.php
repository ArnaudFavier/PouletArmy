<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('utils/tools.php');
require_once('models/ressource.php');

if($_SESSION['ressource']['entrepot'] + 1 == Rules::prochainNiveauEntrepot($_SESSION['ressource']['entrepot'])) { // Augmentation possible ? (si +1 niveau ne dépasse pas la limite)

	if($_SESSION['ressource']['bois'] >= Rules::coutEntrepot(Rules::prochainNiveauEntrepot($_SESSION['ressource']['entrepot']))) { // Ressources suffisantes pour le prochain niveau ?
		// Tableau contenant les nouvelles valeurs des ressources
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::coutEntrepot(Rules::prochainNiveauEntrepot($_SESSION['ressource']['entrepot'])),
			'entrepot' => Rules::prochainNiveauEntrepot($_SESSION['ressource']['entrepot'])
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);

		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Niveau de l\'entrepôt augmenté.', 'graine');
	} else { // Erreur : Ressources insuffisantes
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'graine');
	}
} else { // Erreur : le niveau de l'entrepot dépasse le niveau maximum autorisé
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Niveau maximum de l\'entrepôt atteint.', 'graine');
}

header('Location: graine'); // header() pour recharger les quantités des ressources à afficher
exit;