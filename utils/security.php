<?php

require_once('params/rules.php');

/*
 |------------------------------
 | 		Classe Security
 | Gestion des aspects sécurités du jeu
 |------------------------------
 */

class Security {

	/*
	 * Vérifie l'existence du cookie de sécurité.
	 * True = existe ; False = non existant
	 */
	public static function existSecurityCookie() {
		if (isset($_COOKIE['cookie_security'])) {
			return true;
		}
		return false;
	}

	/*
	 * Créer un cookie contenant un identifiant unique et le stocke en $_SESSION
	 */
	public static function createSecurityCookie() {
		if (!self::existSecurityCookie()) {
			setcookie('cookie_security', 0, time() + (5*24*60*60)); // Durée de cookie : 5 jour
		}
	}

	/*
	 * Test si la création de compte est supérieure à la limite autorisée (true), sinon l'augmente de un (false)
	 */
	public static function inscriptionCounterExceed() {
		if ($_COOKIE['cookie_security'] >= Rules::SECURITY_INSCRIPTION_MAX) {
			return true;
		} else {
			setcookie('cookie_security', $_COOKIE['cookie_security'] + 1, time() + (5*24*60*60)); // Durée de cookie : 5 jour
			return false;
		}
	}

}