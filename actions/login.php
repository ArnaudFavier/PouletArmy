<?php

require_once('utils/tools.php');
require_once('utils/security.php');
require_once('models/user.php');
require_once('models/ressource.php');
require_once('params/rules.php');

/* Cookie accepté pour la session */
if (!Security::existSecurityCookie()) {
	$cookieDisabled = true;
	require_once('views/home.php');
	exit;
}

/* Champs POST existants */
if (!isset($_POST['pseudo'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo manquant.');
}

if (!isset($_POST['password'])) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mot de passe manquant.');
}

/* Champs POST non vides */
$pseudo = Tools::cleanInput($_POST['pseudo']);
if (empty($pseudo)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo vide.');
}

$password = Tools::cleanInputPassword($_POST['password']);
if (empty($password)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mot de passe vide.');
}

/* Longueurs minimales respectées */
if (strlen($pseudo) < Rules::LONGUEUR_MIN_PSEUDO) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo trop court :<br>' . Rules::LONGUEUR_MIN_PSEUDO . ' caractères minimum.');
}

if (strlen($password) < Rules::LONGUEUR_MIN_PASSWORD) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mot de passe trop court :<br>' . Rules::LONGUEUR_MIN_PASSWORD . ' caractères minimum.');
}

/* Longueur maximale respectée */
if (strlen($pseudo) > Rules::LONGUEUR_MAX_PSEUDO) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo trop long :<br>' . Rules::LONGUEUR_MAX_PSEUDO . ' caractères maximum.');
}

if (strlen($password) > Rules::LONGUEUR_MAX_PASSWORD) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Mot de passe trop long :<br>' . Rules::LONGUEUR_MAX_PASSWORD . ' caractères maximum.');
}

/* Pseudo correct (expression régulière match) */
if (!preg_match('/^[a-zA-Z0-9]*$/', $pseudo)) { // Uniquement des lettres et des chiffres
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo invalide :<br>seuls les lettres et les chiffres sont autorisés.');
}
if (!preg_match('/^[a-zA-Z]{3}[a-zA-Z0-9]*$/', $pseudo)) { // 3 lettres au minimum
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Pseudo invalide :<br>doit contenir 3 lettres minimum.');
}

$password = Tools::cryptPassword($password);

/* Instance de User */
try {
	$user = new User($pseudo, $password);
} catch(Exception $e) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], $e->getMessage()); // Le message de l'exception est écrit à la main dans User::get()
}

// Vérification au cas où...
if ($user == null || empty($user)) {
	Tools::message(Tools::TYPE_MESSAGE['Error'], 'Connexion impossible : merci de réssayer.<br>Sinon contacter l\'administrateur.');
}

$_SESSION['user'] = serialize($user);

// Message de bienvenue
Tools::message(Tools::TYPE_MESSAGE['Information'], 'Bienvenue ' . $user->pseudo . ' !', 'game');

header('Location: game');
exit;