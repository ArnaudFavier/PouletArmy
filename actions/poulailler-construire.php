<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('models/poulet.php');
require_once('utils/tools.php');
require_once('models/ressource.php');

$poulailler = Poulet::getPoulailler(unserialize($_SESSION['user'])->id);

// Si le poulailler est déjà construit
if ($poulailler > 0) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Poulailler déjà construit.', 'poulailler');
} else {
	// S'il y a suffisament de bois pour la construction
	if ($_SESSION['ressource']['bois'] >= Rules::COUT_POULAILLER_CONSTRUCTION) {
		// Tableau contenant les nouvelles valeurs des ressources et bâtiments
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::COUT_POULAILLER_CONSTRUCTION,
			'poulailler' => 1
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);

		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Poulailler construit.', 'poulailler');
	} else {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'poulailler');
	}
}

header('Location: poulailler'); // Redirection vers le poulailler
exit;