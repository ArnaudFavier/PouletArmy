<?php

$title = 'Missions';

require_once('connected.php');
require_once('models/mission.php');

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view = array(
	'mission' => Mission::MISSION,
);

require_once('views/game/mission.php');