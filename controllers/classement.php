<?php

$title = 'Classement';

require_once('connected.php');
require_once('models/user.php');

$classement = User::getClassement();

/*
 |------------------------------
 | Affectation des variables d'affichage
 |------------------------------
 */

$view['classement'] = $classement;

require_once('views/game/classement.php');