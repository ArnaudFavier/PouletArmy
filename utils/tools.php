<?php

require_once('params/config.php');

/*
 |------------------------------
 | 		Classe Tools
 | Ensemble de fonctions utiles.
 |------------------------------
 */

class Tools {

	/*
	 |------------------------------
	 | Secure : Gestion de la sécurité
	 |------------------------------
	 */

	 /*
	  * Crypte un mot de passe avec le sel en Config
	  */
	 public static function cryptPassword($password) {
	 	$password = $password . Config::PASSWORD_SALT;
	 	$password = hash('sha256', $password);

	 	return $password;
	 }

 	/*
	 |------------------------------
	 | Forms : Gestion des formulaires
	 |------------------------------
	 */

 	/*
 	 * Nettoie le champs passé en paramètre
 	 */
	public static function cleanInput($input) {
		$input = trim($input);
		$input = htmlspecialchars($input);
		
		return $input;
	}

	/*
	 * Nettoie le mot de passe passé en paramètre
	 */
	public static function cleanInputPassword($input) {
		$input = htmlspecialchars($input);
		
		return $input;
	}

	/*
	 * Créé le cookie de stockage du pseudo du joueur passé en paramètre
	 */
	public static function setPseudoLastPlayerCookie($pseudo) {
		setcookie('cookie_pseudo', $pseudo, time() + (3*24*60*60)); // Durée de cookie : 3 jour
	}

	/*
	 * Retourne le pseudo du dernier joueur connecté sur le périphérique, ou false
	 */
	public static function getPseudoLastPlayerCookie() {
		if (isset($_COOKIE['cookie_pseudo'])) {
			return $_COOKIE['cookie_pseudo'];
		} else {
			return false;
		}
	}

	/*
	 |------------------------------
	 | Messages : Gestion des messages
	 |------------------------------
	 */

	/*
	 * Liste des différents types de messages
	 */
	const TYPE_MESSAGE = [
		'Error' => 0,
		'Warning' => 1,
		'Information' => 2,
		'Success' => 3
	];

	/*
	 * Met le message dans la liste des messages du type correspondant
	 */
	public static function putMessage($type, $message) {
		// array_push($array, $value) -> manuel recommande d'utiliser l'opérateur $array[] = $value
		$typeTranscrit = 'error';
		switch($type) {
			case self::TYPE_MESSAGE['Error']:
				$typeTranscrit = 'error';
				break;
			case self::TYPE_MESSAGE['Warning']:
				$typeTranscrit = 'warning';
				break;
			case self::TYPE_MESSAGE['Information']:
				$typeTranscrit = 'information';
				break;
			case self::TYPE_MESSAGE['Success']:
				$typeTranscrit = 'success';
				break;
			default:
				$typeTranscrit = 'error';
				break;
		}

		// Vérification pas de doublons
		if (!isset($_SESSION['message'][$typeTranscrit]) ||
		in_array($message, $_SESSION['message'][$typeTranscrit]) == false) {
			// Ajout
			$_SESSION['message'][$typeTranscrit][] = $message;
		}
	}

	/*
	 * Met le message dans la liste des messages du type correspondant et renvoie sur le controlleur passé en paramètre
	 */
	public static function message($type, $message, $controller = 'home') {
		self::putMessage($type, $message);
		
		header('Location: ' . $controller);
		exit;
	}

	/*
	 |------------------------------
	 | Affichage : Esthétique d'affichage
	 |------------------------------
	 */

	/*
	 * Formate le nombre passé en paramètre selon un format identique pour toutes les ressources
	 */
	public static function formatNumber($nombre) {
		return number_format($nombre, 0, '', ' ');
	}

}