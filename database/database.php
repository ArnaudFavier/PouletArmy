<?php

require_once('params/config.php');

/*
 |------------------------------
 | 		Classe Database
 | Couche d'abstraction pour les interactions
 | avec la base de données.
 |------------------------------
 */

class Database {

	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Etablissement de la connexion à la base de données au format PDO.
	 * Retourne l'objet PDO d'accès à la base de données.
	 * Meurt et affiche un message d'erreur si connexion impossible.
	 */
	private static function connexion() {
		try {
			return new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_LOGIN, Config::DB_PASSWORD);
		} catch(Exception $e) {
			// die('Erreur de connexion a la base de donnees : ' . $e->getMessage());
			die("Erreur de connexion a la base de donnees. Merci de contacter l'administrateur.");
		}
	}

	/*
	 * Requête SELECT
	 * Prend en paramètres la requête elle-même (au format string)
	 * et le tableau contenant les paramètres de la requête.
	 */
	public static function select($query, $array = array()) {
		$db = self::connexion();

		$statement = $db->prepare($query);

		for ($i = 0; $i < count($array); ++$i) {

			$paramType = PDO::PARAM_STR;

			if ($array[$i][2] == 'STR') {
				$paramType = PDO::PARAM_STR;
			} elseif ($array[$i][2] == 'INT') {
				$paramType = PDO::PARAM_INT;
			}

			$statement->bindValue($array[$i][0], $array[$i][1], $paramType);
		}

		$statement->execute();
		$result = $statement->fetchAll();

		if (empty($result)) {
			$result = null;
		} elseif (count($result) == 1) {
			// TODO : gros problème avec le SELECT de Database::, qui ne renvoit que le contenu du premier résultat s'il n'y a qu'un seul résultat.
			// 				Mais doit changer toute l'application pour résoudre ça, et effectuer des vérifications.
			$result = $result[0];
		}

		$db = null;

		return $result;
	}

	/*
	 * Requête INSERT
	 * Prend en paramètres la requête elle-même (au format string)
	 * et le tableau contenant les paramètres de la requête.
	 */
	public static function insert($query, $array) {
		$db = self::connexion();

		$statement = $db->prepare($query);

		for ($i = 0; $i < count($array); ++$i) {

			$paramType = PDO::PARAM_STR;

			if ($array[$i][2] == 'STR') {
				$paramType = PDO::PARAM_STR;
			} elseif ($array[$i][2] == 'INT') {
				$paramType = PDO::PARAM_INT;
			}

			$statement->bindValue($array[$i][0], $array[$i][1], $paramType);
		}

		$result = $statement->execute();

		$db = null;

		return $result;
	}

	/*
	 * Requête UPDATE
	 * Prend en paramètres la requête elle-même (au format string)
	 * et le tableau contenant les paramètres de la requête.
	 */
	public static function update($query, $array) {
		$db = self::connexion();

		$statement = $db->prepare($query);

		for ($i = 0; $i < count($array); ++$i) {

			$paramType = PDO::PARAM_STR;

			if ($array[$i][2] == 'STR') {
				$paramType = PDO::PARAM_STR;
			} elseif ($array[$i][2] == 'INT') {
				$paramType = PDO::PARAM_INT;
			}

			$statement->bindValue($array[$i][0], $array[$i][1], $paramType);
		}

		$statement->execute();

		$db = null;

		return $statement->rowCount() ? true : false;
	}

	/*
	 * Requête DELETE
	 * Prend en paramètres la requête elle-même (au format string)
	 * et le tableau contenant les paramètres de la requête.
	 */
	public static function delete($query, $array) {
		$db = self::connexion();

		$statement = $db->prepare($query);

		for ($i = 0; $i < count($array); ++$i) {

			$paramType = PDO::PARAM_STR;

			if ($array[$i][2] == 'STR') {
				$paramType = PDO::PARAM_STR;
			} elseif ($array[$i][2] == 'INT') {
				$paramType = PDO::PARAM_INT;
			}

			$statement->bindValue($array[$i][0], $array[$i][1], $paramType);
		}

		$result = $statement->execute();

		$db = null;

		return $result;
	}
	
}
