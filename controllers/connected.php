<?php

require_once('utils/tools.php');
require_once('models/user.php');
require_once('models/ressource.php');
require_once('models/rapport.php');

/* Vérification connexion */
if(!isset($_SESSION['user'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Vous devez être connecté pour accéder à cette page.');
}

/* Mise à jour des ressources */
$idUser = unserialize($_SESSION['user'])->id;
Ressource::update($idUser);
Ressource::getIntoSession();
User::updatePointSession($idUser);

/* Affichage des rapports */
if (((isset($title) && $title != 'Rapports') || (!isset($title)))
	 && Rapport::check(unserialize($_SESSION['user'])->id)) {
	Tools::putMessage(Tools::TYPE_MESSAGE['Warning'], 'Vous avez été attaqués ! <a href="rapport" class="normal"><span class="glyphicon glyphicon-arrow-right"></span> Rapports</a>');
}