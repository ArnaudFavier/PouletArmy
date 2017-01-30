<?php

require_once('database/database.php');
require_once('params/config.php');
require_once('params/rules.php');
require_once('user.php');

/*
 |------------------------------
 | 		Classe Rapport
 | Manipulation des rapports de
 | combats entre les joueurs
 |------------------------------
 */

class Rapport {
	
	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Insère un rapport en base de données
	 */
	public static function insert($dateAttaque, $idDefenseur, $idAttaquant, $nbPouletAttaquant, $nbPouletDefenseurPerdu, $boisPille = 0, $grainePille = 0) {
		// Compte le nombre de rapports du défenseur
		$query = 'SELECT id FROM ' . Config::DB_TABLE_PREFIX . 'rapport WHERE idDefenseur=:idDefenseur ORDER BY dateAttaque ASC;';
		$result = Database::select($query, [
			[':idDefenseur', $idDefenseur, 'INT']
		]);

		// Vérifie si il s'agit du contenu d'un seul rapport, ou s'il y a plusieurs rapports
		// Si un seul rapport, alors mit dans un tableau
		// Si vide, on retourne la variable dans son état actuel (null)
		if (!empty($result) && !is_array($result[0])) {
			$result = array($result);
		}

		// Plus de rapport que le maximum prévu ? Suppression en base de données
		while (count($result) >= Rules::NB_RAPPORT) { // >= car on ajoute un nouveau rapport ensuite
			$query = 'DELETE FROM ' . Config::DB_TABLE_PREFIX . 'rapport WHERE id=:id;';
			Database::delete($query, [
				[':id', $result[0]['id'], 'INT']
			]);
			array_shift($result);
		}

		// Insertion du nouveau rapport
		$query = 'INSERT INTO ' . Config::DB_TABLE_PREFIX . 'rapport (dateAttaque, idDefenseur, idAttaquant, nbPouletAttaquant, nbPouletDefenseurPerdu, lu, bois, graine) VALUES (:dateAttaque, :idDefenseur, :idAttaquant, :nbPouletAttaquant, :nbPouletDefenseurPerdu, :lu, :bois, :graine);';
		$result = Database::insert($query, [
			[':dateAttaque', $dateAttaque, 'INT'],
			[':idDefenseur', $idDefenseur, 'INT'],
			[':idAttaquant', $idAttaquant, 'INT'],
			[':nbPouletAttaquant', $nbPouletAttaquant, 'INT'],
			[':nbPouletDefenseurPerdu', $nbPouletDefenseurPerdu, 'INT'],
			[':lu', false, 'INT'],
			[':bois', $boisPille, 'INT'],
			[':graine', $grainePille, 'INT']
		]);
	}

	/*
	 * Retourne tous les rapports du joueur défenseur $idDefenseur passé en paramètre
	 */
	public static function getAll($idDefenseur) {
		// Marque tous les rapports du joueur défenseur ($idDefenseur) comme étant déjà vu (lu = true) 
		// pour désactiver le message (notification de nouveaux rapports)
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'rapport SET lu=true WHERE idDefenseur=:idDefenseur;';
		$result = Database::update($query, [
			[':idDefenseur', $idDefenseur, 'INT']
		]);

		// Récupère tous les rapports
		$query = 'SELECT * FROM ' . Config::DB_TABLE_PREFIX . 'rapport WHERE idDefenseur=:idDefenseur ORDER BY dateAttaque DESC;';
		$result = Database::select($query, [
			[':idDefenseur', $idDefenseur, 'INT']
		]);

		// TODO : gros problème avec le SELECT de Database:: qui ne renvoit que le contenu du premier résultat s'il n'y a qu'un seul résultat.
		// 				Mais doit changer toute l'application pour résoudre ça, et effectuer des vérifications.
		//				A améliorer dans un futur, mais fonctionne comme cela pour le moment

		// Vérifie si il s'agit du contenu d'un seul rapport, ou s'il y a plusieurs rapports
		// Si un seul rapport, alors mit dans un tableau
		// Si vide, on retourne la variable dans son état actuel (null)
		if (!empty($result) && !is_array($result[0])) {
			$result = array($result);
		}

		return $result;
	}
	
	/*
	 * Retourne true si il y a des rapports non lu, false sinon
	 */
	public static function check($idDefenseur) {
		// Récupère les rapports non lu
		$query = 'SELECT id FROM ' . Config::DB_TABLE_PREFIX . 'rapport WHERE idDefenseur=:idDefenseur AND lu=false;';
		$result = Database::select($query, [
			[':idDefenseur', $idDefenseur, 'INT']
		]);

		// S'il y a des rapport non lu : true
		if (!empty($result)) {
			return true;
		} else { // Sinon false
			return false;
		}
	}
}