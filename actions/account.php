<?php

require_once('utils/tools.php');
require_once('models/user.php');
require_once('params/rules.php');

/* Champs POST existants */
if (!isset($_POST['password'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Nouveau mot de passe manquant.', 'account');
}

if (!isset($_POST['password-confirmation'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Confirmation du mot de passe manquante.', 'account');
}

/* Champs POST non vides */
$password = Tools::cleanInputPassword($_POST['password']);
if (empty($password)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Nouveau mot de passe vide.', 'account');
}

$passwordConfirmation = Tools::cleanInputPassword($_POST['password-confirmation']);
if (empty($passwordConfirmation)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Confirmation du mot de passe vide.', 'account');
}

/* Longueurs minimales respectées */
if (strlen($password) < Rules::LONGUEUR_MIN_PASSWORD) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Nouveau mot de passe trop court :<br>' . Rules::LONGUEUR_MIN_PASSWORD . ' caractères minimum.', 'account');
}

/* Longueur maximale respectée */
if (strlen($password) > Rules::LONGUEUR_MAX_PASSWORD) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Nouveau mot de passe trop long :<br>' . Rules::LONGUEUR_MAX_PASSWORD . ' caractères maximum.', 'account');
}

/* Mots de passe ne correspondent pas */
if ($password != $passwordConfirmation) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Le nouveau mot de passe et la confirmation ne correspondent pas.', 'account');
}

$password = Tools::cryptPassword($password);

$result = User::updatePassword(unserialize($_SESSION['user'])->id, $password);

// Modification retourne false c'est qu'il y a eu un problème
if ($result == false) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Modification du mot de passe impossible.<br>Merci de réessayer.', 'account');
}

// Message de confirmation
Tools::message(Tools::TYPE_MESSAGE['Success'], 'Changement effectué avec succès.', 'account');

header('Location: account');
exit;