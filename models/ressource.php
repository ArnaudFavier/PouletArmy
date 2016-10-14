<?php

require_once('database/database.php');
require_once('params/config.php');
require_once('params/rules.php');

/*
 |------------------------------
 | 		Classe Ressource
 | Interactions entre les ressources des joueurs,
 | et la base de données.
 |------------------------------
 */

class Ressource {
	
	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Mets à jour en base de données les ressources du joueur passé en paramètre avec le timestamp actuel
	 */
	public static function update($idUser) {
		/* Récupération */
		$query = 'SELECT lastUpdate, bois, scierie, depot, graine, champs, entrepot FROM ' . Config::DB_TABLE_PREFIX . 'ressource WHERE idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

		/* Calculs */
		$time = time();

		$diffenceTemps = $time - $result['lastUpdate'];

		/* Bois */
		$bois = $result['bois'];
		$boisMax = Rules::boisMaximum($result['depot']);

		if($bois < $boisMax) { // Calcul uniquement s'il y a du bois à gagner (permet de garder du bois en plus, récupéré lors de mission par exemple)
			$bois = $result['bois'] + $diffenceTemps * Rules::boisSeconde($result['scierie']);
			if($bois >= $boisMax) {
				$bois = $boisMax;
			}
		}

		/* Graine */
		$graine = $result['graine'];
		$graineMax = Rules::graineMaximum($result['entrepot']);

		if($graine < $graineMax) { // Calcul uniquement s'il y a des graines à gagner (permet de garder des graines en plus, récupérées lors de mission par exemple)
			$graine = $result['graine'] + $diffenceTemps * Rules::graineSeconde($result['champs']);
			if($graine >= $graineMax) {
				$graine = $graineMax;
			}
		}

		/* Mise à jour */
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'ressource SET lastUpdate=:lastUpdate, bois=:bois, graine=:graine WHERE idUser=:idUser;';
		$result = Database::update($query, [
			[':idUser', $idUser, 'INT'],
			[':lastUpdate', $time, 'INT'],
			[':bois', $bois, 'STR'],
			[':graine', $graine, 'STR']
		]);
	}

	/*
	 * Récupère et retourne les ressources du joueur passé en paramètre
	 */
	public static function get($idUser) {
		$query = 'SELECT bois, scierie, depot, graine, champs, entrepot, `or` FROM ' . Config::DB_TABLE_PREFIX . 'ressource WHERE idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

		return $result;
	}

	/*
	 * Récupère les ressources du joueur actuel (id en session) puis les mets en session
	 */
	public static function getIntoSession() {
		$ressource = Ressource::get(unserialize($_SESSION['user'])->id);

		$_SESSION['ressource']['bois'] = intval($ressource['bois']);
		$_SESSION['ressource']['graine'] = intval($ressource['graine']);
		$_SESSION['ressource']['or'] = intval($ressource['or']);

		$_SESSION['ressource']['scierie'] = $ressource['scierie'];
		$_SESSION['ressource']['depot'] = $ressource['depot'];
		$_SESSION['ressource']['champs'] = $ressource['champs'];
		$_SESSION['ressource']['entrepot'] = $ressource['entrepot'];

		$_SESSION['ressource']['boisMax'] = Rules::boisMaximum($ressource['depot']);
		$_SESSION['ressource']['graineMax'] = Rules::graineMaximum($ressource['entrepot']);

		$_SESSION['ressource']['boisSeconde'] = Rules::boisSeconde($ressource['scierie']);
		$_SESSION['ressource']['graineSeconde'] = Rules::graineSeconde($ressource['champs']);
	}

	/*
	 * Modifie le contenu des ressources du joueur passé en paramètre avec le tableau de valeurs passé en paramètre
	 */
	public static function modify($idUser, $nouvellesRessources) {
		$bindingFinal = array(); // Tableau final contenant les données qui vont être modifiées en base de données

		/* Construction de la requete */
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'ressource SET ';
		$compte = 1; // Pour que la syntaxe de la requête soit correcte, il ne faut pas de virgule après le dernier attribut
		foreach ($nouvellesRessources as $cle => $valeur) {
			$nomBinding = ':' . $cle;
			$query .= $cle . '=' . $nomBinding;
			
			if($compte < count($nouvellesRessources)) {
				$query .= ', ';
			} else {
				$query .= ' ';
			}
			$compte++;

			$bindingFinal[] = [$nomBinding, $valeur, 'STR'];
		}
		$query .= 'WHERE idUser=:idUser;';
		$bindingFinal[] = [':idUser', $idUser, 'INT'];

		/* Modification en base de donnée */
		$result = Database::update($query, $bindingFinal);
	}
	
}