<?php

require_once('database/database.php');
require_once('params/config.php');
require_once('params/rules.php');
require_once('user.php');

/*
 |------------------------------
 | 		Classe Poulet
 | Obtention des poulets en base de données 
 | et gestion de ces derniers.
 |------------------------------
 */

class Poulet {
	
	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Récupère la liste de tous les poulets stockés en base de données
	 */
	public static function getAll() {
		$query = 'SELECT * FROM ' . Config::DB_TABLE_PREFIX . 'poulet;';
		$result = Database::select($query);

		return $result;
	}

	/*
	 * Récupère la liste de tous les poulets et leur nombre possédé dans l'armée du joueur $idUser
	 */
	public static function getAllWithArmy($idUser) {
		// FULL OUTER JOIN n'existe pas en MySql, donc LEFT UNION RIGHT pour
		// récupérer les poulets dont on n'a pas encore la quanitité en BDD
		$query = 'SELECT * FROM ' . Config::DB_TABLE_PREFIX . 'poulet p LEFT JOIN ' . Config::DB_TABLE_PREFIX . 'army a ON p.id = a.idPoulet AND idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

		return $result;
	}

	/*
	 * Récupère l'armée stocké en base de données du joueur $idUser passé en paramètre
	 * avec les informations des poulets correspondant
	 */
	public static function getArmyWithPoulet($idUser) {
		$query = 'SELECT * FROM ' . Config::DB_TABLE_PREFIX . 'army a INNER JOIN ' . Config::DB_TABLE_PREFIX . 'poulet p ON a.idPoulet = p.id AND idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

		return $result;
	}

	/*
	 * Compte le nombre de poulets possédés par le joueur $idUser passé en paramètre
	 */
	public static function countPoulet($idUser) {
		$query = 'SELECT SUM(quantite) FROM ' . Config::DB_TABLE_PREFIX . 'army a WHERE idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

 		// $result retourne un tableau de type double array ['sum' => X, '0' => X]
		$result = $result[0];

		// Si pas de quantite existante (pas de tuple dans army)
		if(is_null($result)) {
			$result = 0;
		}

		return $result;
	}

	/*
	 * Modifie la quantite $quantite de poulet $idPoulet de l'utilisateur $idUser
	 */
	public static function setQuantite($idUser, $idPoulet, $quantite) {
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'army SET quantite=:quantite WHERE idUser=:idUser AND idPoulet=:idPoulet;';
		$result = Database::update($query, [
			[':idUser', $idUser, 'INT'],
			[':idPoulet', $idPoulet, 'INT'],
			[':quantite', $quantite, 'STR']
		]);

		// Mise à jour du nombre de points du joueur
		User::updatePoint($idUser);

		return $result;
	}

	/*
	 * Ajoute dans l'armée (la table army) de l'utilisateur $idUser,
	 * les informations du poulet $poulet avec la quantite $quantite
	 */
	public static function insertArmy($idUser, $poulet, $quantite) {
		$query = 'INSERT INTO ' . Config::DB_TABLE_PREFIX . 'army (idUser, idPoulet, quantite, debloque) VALUES (:idUser, :idPoulet, :quantite, :debloque);';
		$result = Database::update($query, [
			[':idUser', $idUser, 'INT'],
			[':idPoulet', $poulet['id'], 'INT'],
			[':quantite', $quantite, 'STR'],
			[':debloque', $poulet['debut'], 'INT']
		]);

		// Mise à jour du nombre de points du joueur
		User::updatePoint($idUser);
	}

	/*
	 * Retourne le niveau du poulailler en base de données du joueur $idUser
	 */
	public static function getPoulailler($idUser) {
		$query = 'SELECT poulailler FROM ' . Config::DB_TABLE_PREFIX . 'ressource WHERE idUser=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);

		// Retourne uniquement le poulailler
		return $result['poulailler'];
	}
	
}