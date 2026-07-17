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

	/* Connexion partagée : une seule par requête HTTP (nécessaire en BD distante sur Vercel) */
	private static $pdo = null;

	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Etablissement de la connexion à la base de données au format PDO.
	 * Retourne l'objet PDO d'accès à la base de données.
	 * Les variables d'environnement (Vercel) priment sur les constantes de Config.
	 * Meurt et affiche un message d'erreur si connexion impossible.
	 */
	private static function connexion() {
		if (self::$pdo !== null) {
			return self::$pdo;
		}
		// PHP 8 passe PDO en mode exception par défaut : on conserve le comportement historique
		$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);

		// TLS si demandé : DB_SSL_CA contient soit un chemin de fichier, soit le contenu PEM du certificat CA lui-même (écrit alors dans un fichier temporaire, PDO exigeant un chemin)
		// PDO::MYSQL_ATTR_SSL_CA est déprécié depuis PHP 8.5 au profit de Pdo\Mysql::ATTR_SSL_CA
		if (getenv('DB_SSL_CA')) {
			$sslCa = getenv('DB_SSL_CA');
			if (strpos($sslCa, '-----BEGIN') !== false) {
				$sslCaFichier = sys_get_temp_dir() . '/db-ssl-ca.pem';
				if (!file_exists($sslCaFichier)) {
					file_put_contents($sslCaFichier, $sslCa);
				}
				$sslCa = $sslCaFichier;
			}
			$options[class_exists('Pdo\\Mysql') ? Pdo\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
		}

		try {
			self::$pdo = new PDO(
				'mysql:host=' . (getenv('DB_HOST') ?: Config::DB_HOST) . ';port=' . (getenv('DB_PORT') ?: '3306') . ';dbname=' . (getenv('DB_NAME') ?: Config::DB_NAME) . ';charset=utf8',
				getenv('DB_LOGIN') ?: Config::DB_LOGIN,
				getenv('DB_PASSWORD') ?: Config::DB_PASSWORD,
				$options
			);
			return self::$pdo;
		} catch(Exception $e) {
			error_log('Erreur PDO : ' . $e->getMessage());
			die("Erreur de connexion a la base de donnees. Merci de contacter l'administrateur.");
		}
	}

	/*
	 * Accès direct à l'objet PDO (utilisé par le gestionnaire de sessions).
	 */
	public static function getPdo() {
		return self::connexion();
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
