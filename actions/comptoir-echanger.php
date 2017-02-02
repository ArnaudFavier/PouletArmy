<?php

require_once('controllers/connected.php');
require_once('params/rules.php');
require_once('models/ressource.php');
require_once('utils/tools.php');

$comptoir = Ressource::getComptoir(unserialize($_SESSION['user'])->id);

// Si le comptoir n'est pas construit
if ($comptoir < 1) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Comptoir non construit.', 'or');
} else {
	// S'il y a suffisament de bois pour l'échange
	if ($_SESSION['ressource']['bois'] >= Rules::COUT_COMPTOIR_ECHANGE) {
		// Tableau contenant les nouvelles valeurs des ressources et bâtiments
		$nouvellesRessources = array(
			'bois' => $_SESSION['ressource']['bois'] - Rules::COUT_COMPTOIR_ECHANGE,
			'`or`' => $_SESSION['ressource']['or'] + Rules::GAIN_COMPTOIR
		);

		Ressource::modify(unserialize($_SESSION['user'])->id, $nouvellesRessources);
		
		Tools::message(Tools::TYPE_MESSAGE['Success'], 'Bois échangé.', 'or');
	} else {
		Tools::message(Tools::TYPE_MESSAGE['Error'], 'Ressources en bois insuffisantes.', 'or');
	}
}

header('Location: or'); // Redirection vers l'or
exit;