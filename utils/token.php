<?php

/*
 |------------------------------
 | 		Classe Token
 | Gestion des tokens de sécurité des formulaires.
 |------------------------------
 */

/*
 * Génère un nouveau token unique, le stoke en $_SESSION et le retourne
 */
public static function createToken() {
	/* Suppression précédent token */
	if (isset($_SESSION['token'])) {
		unset($_SESSION['token']);
	}
	if (isset($_SESSION['token_time'])) {
		unset($_SESSION['token_time']);
	}

	/* Création nouveau token */
	$token = md5(openssl_random_pseudo_bytes(8));
	$_SESSION['token'] = $token;
	$_SESSION['token_time'] = time();

	return $token;
}

/*
 * Vérifie que le token passé en paramètre existe et est valide (identique à celui en $_SESSION + temps respecté)
 */
public static function verifyToken($token) {
	if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
		if ($token == $_SESSION['token']) {
			if (($_SESSION['token_time'] + 60*60) > time()) {
				return true;
			}
		}
	}
	return false;
}