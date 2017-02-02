<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('models/ressource.php');
require_once('utils/tools.php');

$comptoir = Ressource::getComptoir(unserialize($_SESSION['user'])->id);

// Si le comptoir est déjà construit
if ($comptoir > 0) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Comptoir déjà construit.', 'or');
} else {
	// S'il y a suffisament de bois pour la construction
	if ($_SESSION['ressource']['bois'] >= Rules::COUT_COMPTOIR_CONSTRUCTION) {
		// Tableau contenant les nouvelles valeurs des ressources et bâtiments
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::COUT_COMPTOIR_CONSTRUCTION,
			'comptoir' => 1
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);

		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Comptoir construit.', 'or');
	} else {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'or');
	}
}

header('Location: or'); // Redirection vers l'or
exit;