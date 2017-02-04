<?php

require_once('database/database.php');
require_once('params/config.php');
require_once('params/rules.php');
require_once('poulet.php');

/*
 |------------------------------
 | 		Classe User
 | Gestion des informations l'utilisateur 
 | qui se connecte et des informations d'autres joueurs.
 |------------------------------
 */

class User {

	/*
	 |------------------------------
	 | Attributs
	 |------------------------------
	 */

	/*
	 * id = identifiant unique du joueur (id en base de donnée)
	 * pseudo = pseudonyme du joueur
	 * inscription = date d'inscription du joueur
	 * point = nombre de points du joueur
	 */
	public $id;
	public $pseudo;
	public $inscription;
	public $point;

	/*
	 |------------------------------
	 | Constructeur
	 |------------------------------
	 */

	/*
	 * Constructeur, appellé lors de la connexion/inscription d'un joueur
	 */
	public function __construct($pseudo, $password) {
		$user = null;

		$user = $this->get($pseudo, $password);

		if ($user == null) { // Création
			// Vérification pas de dépassement du nombre max de création de comptes
			if (Security::inscriptionCounterExceed() == true) {
				throw new Exception('Inscription impossible :<br>limite de création de compte atteinte.');
			} else {
				$this->create($pseudo, $password);
				$user = $this->get($pseudo, $password);
			}
		}

		return $user;
	}

	/*
	 |------------------------------
	 | Fonctions
	 |------------------------------
	 */

	/*
	 * Créer le joueur dans la base de données :
	 * vérification + ajout dans toutes les tables des informations nécéssaires (clés étrangères)
	 */
	private function create($pseudo, $password) {
		if ($this->exist($pseudo)) { // Pseudo existe déjà
			throw new Exception('Le pseudo existe déjà.');
		} else {
			/* --- Ajout de l'utilisateur --- */
			/* User */
			$query = 'INSERT INTO ' . Config::DB_TABLE_PREFIX . 'user (pseudo, password, inscription, point) VALUES (:pseudo, :password, :inscription, 0);';
			$result = Database::insert($query, [
				[':pseudo', $pseudo, 'STR'],
				[':password', $password, 'STR'],
				[':inscription', time(), 'INT']
			]);

			if ($result == false) {
				throw new Exception('Erreur dans l\'insertion en base de données.<br>Merci de contacter l\'administrateur.');
			}

			// Récupération de l'identifiant du joueur créé
			$result = $this->get($pseudo, $password);
			$idUser = $result['id'];

			/* Ressource */
			$query = 'INSERT INTO ' . Config::DB_TABLE_PREFIX . 'ressource (idUser, lastUpdate, bois, scierie, depot, graine, champs, entrepot, `or`, poulailler, laboratoire, comptoir) VALUES (:idUser, :lastUpdate, :bois, :scierie, :depot, :graine, :champs, :entrepot, :_or, :poulailler, :laboratoire, :comptoir);';
			$result = Database::insert($query, [
				[':idUser', $idUser, 'INT'],
				[':lastUpdate', time(), 'INT'],
				[':bois', Rules::DEPART_BOIS, 'STR'],
				[':scierie', Rules::DEPART_SCIERIE, 'STR'],
				[':depot', Rules::DEPART_DEPOT, 'STR'],
				[':graine', Rules::DEPART_GRAINE, 'STR'],
				[':champs', Rules::DEPART_CHAMPS, 'STR'],
				[':entrepot', Rules::DEPART_ENTREPOT, 'STR'],
				[':_or', Rules::DEPART_OR, 'STR'],
				[':poulailler', Rules::DEPART_POULAILLER, 'STR'],
				[':laboratoire', Rules::DEPART_LABORATOIRE, 'STR'],
				[':comptoir', Rules::DEPART_COMPTOIR, 'STR']
			]);

			if ($result == false) {
				throw new Exception('Erreur dans l\'insertion en base de données.<br>Merci de contacter l\'administrateur.');
			}

			/* Army */
			$listePoulets = Poulet::getAll();
			foreach ($listePoulets as $poulet) {
				Poulet::insertArmy($idUser, $poulet, 0);
			}
		}
	}

	/*
	 * Récupère le joueur correspondant au pseudo et mot de passe passé en paramètre
	 */
	private function get($pseudo, $password) {
		$query = 'SELECT id, pseudo, inscription, point FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE pseudo=:pseudo AND password=:password;';
		$result = Database::select($query, [
			[':pseudo', $pseudo, 'STR'],
			[':password', $password, 'STR']
		]);

		if ($result == null) {
			if ($this->exist($pseudo)) { // Le pseudo existe
				throw new Exception('Mot de passe inccorect<br>ou pseudo déjà existant.');
			}
		} else {
			$this->id = $result['id'];
			$this->pseudo = $result['pseudo'];
			$this->inscription = $result['inscription'];
			$this->point = $result['point'];
		}

		return $result;
	}

	/*
	 * Vérifie si le pseudo (donc le joueur) est déjà existant dans la base de données
	 */
	private function exist($pseudo) {
		$query = 'SELECT pseudo FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE pseudo=:pseudo;';
		$result = Database::select($query, [
			[':pseudo', $pseudo, 'STR']
		]);

		if ($result == null) { // Le pseudo n'existe pas
			return false;
		} else { // Le pseudo existe déjà
			return true;
		}
	}

	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Calcule le nombre de points du joueur $idUser, et met à jour en BD
	 * et en session si utilisateur courant le nouveau nombre de points.
	 * Formule : Pour chaque poulet : quantité possédée * puissance d'attaque
	 */
	public static function updatePoint($idUser) {
		$point = 0;

		/* Calcul */
		$listePoulet = Poulet::getAllWithArmy($idUser);
		foreach ($listePoulet as $poulet) {
			if (!is_null($poulet['quantite'])) {
				$point += $poulet['quantite'] * $poulet['attaque'];
			}
		}

		/* Mise à jour */
		// BD
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'user SET point=:point WHERE id=:idUser;';
		$result = Database::update($query, [
			[':idUser', $idUser, 'INT'],
			[':point', $point, 'INT']
		]);

		// Session (si id utilisateur courant existe et est le même que celui passé en paramètre)
		if (isset($_SESSION['user'])) {
			$user = unserialize($_SESSION['user']);
			if ($user->id == $idUser) {
				$user->point = $point;
				$_SESSION['user'] = serialize($user);
			}
		}

		return $point;
	}

	/*
	 * Met à jour le nombre de point du joueur courant en $_SESSION si besoin
	 */
	public static function updatePointSession($idUser) {
		$query = 'SELECT point FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE id=:idUser;';
		$result = Database::select($query, [
			[':idUser', $idUser, 'INT']
		]);
		$point = $result['point'];

		if ($point != null) {
			$user = unserialize($_SESSION['user']);
			if ($user->id == $idUser && $user->point != $point) {
				$user->point = $point;
				$_SESSION['user'] = serialize($user);
			}
		}
	}

	/*
	 * Retourne le classement du nombre "Rules::NB_JOUEUR_PAGE" de joueurs
	 * ayant le plus de points, par ordre décroissant, suivant le numéro de
	 * page $page passé en paramètre.
	 */
	public static function getClassementPoints($page = 0) {
		$query = 'SELECT pseudo, point FROM ' . Config::DB_TABLE_PREFIX . 'user ORDER BY point DESC LIMIT :offset, :limite;';
		$result = Database::select($query, [
			[':offset', $page * Rules::NB_JOUEUR_PAGE, 'INT'],
			[':limite', Rules::NB_JOUEUR_PAGE, 'INT']
		]);

		// Vérifie s'il n'y a eu qu'un seul résultat
		if (!empty($result) && !is_array($result[0])) {
			$result = array($result);
		}

		return $result;
	}

	/*
	 * Retourne le classement du nombre "Rules::NB_JOUEUR_PAGE" de joueurs
	 * ayant le plus d'or, par ordre décroissant, suivant le numéro de
	 * page $page passé en paramètre.
	 */
	public static function getClassementOr($page = 0) {
		$query = 'SELECT pseudo, `or` FROM ' . Config::DB_TABLE_PREFIX . 'user u INNER JOIN ' . Config::DB_TABLE_PREFIX . 'ressource r ON u.id=r.idUser ORDER BY `or` DESC LIMIT :offset, :limite;';
		$result = Database::select($query, [
			[':offset', $page * Rules::NB_JOUEUR_PAGE, 'INT'],
			[':limite', Rules::NB_JOUEUR_PAGE, 'INT']
		]);

		// Vérifie s'il n'y a eu qu'un seul résultat
		if (!empty($result) && !is_array($result[0])) {
			$result = array($result);
		}

		return $result;
	}

	/*
	 * Retourne la liste des joueurs en base de données sauf celui dont l'id $idUser est passé en paramètre
	 * avec leur id et leur pseudo.
	 */
	public static function getListeJoueursWithoutId($idUser) {
		$query = 'SELECT id, pseudo FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE id!=:id ORDER BY pseudo ASC;';
		$result = Database::select($query, [
			[':id', $idUser, 'INT']
		]);

		// Vérifie s'il n'y a eu qu'un seul résultat
		if (!empty($result) && !is_array($result[0])) {
			$result = array($result);
		}

		return $result;
	}

	/*
	 * Retourne un booléen suivant si l'id $idUser du joueur passé en paramètre
	 * correspond à un joueur qui existe réellement.
	 * 	- true si le joueur existe bien en base de données
	 * 	- false si le joueur n'existe pas en base de données
	 */
	public static function existId($idUser) {
		$query = 'SELECT id FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE id=:id;';
		$result = Database::select($query, [
			[':id', $idUser, 'INT']
		]);
		
		if ($result == null) { // L'id n'existe pas
			return false;
		} else { // L'id existe déjà
			return true;
		}
	}

	/*
	 * Retourne des informations sur le joueur $idUser passé en paramètre
	 */
	public static function getInformations($idUser) {
		$query = 'SELECT id, pseudo, point, inscription FROM ' . Config::DB_TABLE_PREFIX . 'user WHERE id=:id;';
		$result = Database::select($query, [
			[':id', $idUser, 'INT']
		]);
		
		return $result;
	}

	/*
	 * Met à jour le mot de passe $password de l'utilisateur $id en base de données
	 */
	public static function updatePassword($idUser, $password) {
		$query = 'UPDATE ' . Config::DB_TABLE_PREFIX . 'user SET password=:password WHERE id=:idUser;';
		$result = Database::update($query, [
			[':idUser', $idUser, 'INT'],
			[':password', $password, 'STR']
		]);

		return $result;
	}

}