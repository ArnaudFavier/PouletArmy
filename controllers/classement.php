<?php

$title = 'Classement';

require_once('connected.php');
require_once('models/user.php');

$classementPoints = User::getClassementPoints();
$classementOr = User::getClassementOr();

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */
$view = array(
	'classementPoints' => $classementPoints,
	'classementOr' => $classementOr,
);

require_once('views/game/classement.php');