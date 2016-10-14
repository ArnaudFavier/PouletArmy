<?php
session_start();

/*
 |------------------------------
 | Système de gestion de routes
 |------------------------------
 */

if(isset($_GET['url'])) {
	$url = $_GET['url'];
} else {
	$url = '';
}

$defaut = 'controllers/home.php'; // Controller par défaut 

if(isset($_SERVER['REQUEST_METHOD'])) {
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		/*
		 * Ensemble des requêtes GET
		 */
		switch($url) {
			case '':
			case 'home':
				require_once('controllers/home.php');
				break;
			case 'deconnexion':
				require_once('controllers/deconnexion.php');
				break;
			case 'game':
				require_once('controllers/game.php');
				break;
			case 'bois':
				require_once('controllers/bois.php');
				break;
			case 'graine':
				require_once('controllers/graine.php');
				break;
			case 'or':
				require_once('controllers/or.php');
				break;
			case 'poulailler':
				require_once('controllers/poulailler.php');
				break;
			case 'mission':
				require_once('controllers/mission.php');
				break;
			case 'attaque':
				require_once('controllers/attaque.php');
				break;
			case 'rapport':
				require_once('controllers/rapport.php');
				break;
			case 'laboratoire':
				require_once('controllers/laboratoire.php');
				break;
			case 'classement':
				require_once('controllers/classement.php');
				break;
			case 'about':
				require_once('controllers/about.php');
				break;
			case 'help':
				require_once('controllers/help.php');
				break;
			case 'scierie-ameliorer':
				require_once('actions/scierie-ameliorer.php');
				break;
			case 'depot-ameliorer':
				require_once('actions/depot-ameliorer.php');
				break;
			case 'champs-ameliorer':
				require_once('actions/champs-ameliorer.php');
				break;
			case 'entrepot-ameliorer':
				require_once('actions/entrepot-ameliorer.php');
				break;
			case 'poulailler-construire':
				require_once('actions/poulailler-construire.php');
				break;
			default:
				require_once($defaut);
				break;
		}
	} elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
		/*
		 * Ensemble des requêtes POST
		 */
		switch($url) {
			case 'login':
				require_once('actions/login.php');
				break;
			case 'poulet-recruter':
				require_once('actions/poulet-recruter.php');
				break;
			case 'mission-recruter':
				require_once('actions/mission-recruter.php');
				break;
			case 'mission-rapport':
				require_once('actions/mission-rapport.php');
				break;
			case 'attaque-recruter':
				require_once('actions/attaque-recruter.php');
				break;
			case 'attaque-rapport':
				require_once('actions/attaque-rapport.php');
				break;
			default:
				require_once($defaut);
				break;
		}
	} else {
		require_once($defaut);
	}
} else {
	require_once($defaut);
}