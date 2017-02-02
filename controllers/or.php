<?php

$title = 'Or';

require_once('connected.php');
require_once('params/rules.php');
require_once('utils/tools.php');

$comptoir['niveau'] = Ressource::getComptoir(unserialize($_SESSION['user'])->id);
$comptoir['coutConstruction'] = Tools::formatNumber(Rules::COUT_COMPTOIR_CONSTRUCTION);
$comptoir['coutEchange'] = Tools::formatNumber(Rules::COUT_COMPTOIR_ECHANGE);
$comptoir['gainOr'] = Tools::formatNumber(Rules::GAIN_COMPTOIR);

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */
 $view = array(
 	'comptoir' => $comptoir,
);

require_once('views/game/or.php');