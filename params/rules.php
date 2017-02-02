<?php

/*
 |------------------------------
 | 		Classe Rules
 | Les règles et options du jeu.
 |------------------------------
 */

class Rules {

	/*
	 |------------------------------
	 | Constantes du jeu
	 |------------------------------
	 */

	/*
	 * Au début du jeu, le départ d'un nouveau joueur
	 */

	/* Ressources */
	const DEPART_BOIS = 450;
	const DEPART_GRAINE = 0;
	const DEPART_OR = 0;

	/* Batiments */
	const DEPART_SCIERIE = 0;
	const DEPART_DEPOT = 0;
	const DEPART_CHAMPS = 0;
	const DEPART_ENTREPOT = 0;
	const DEPART_POULAILLER = 0;
	const DEPART_LABORATOIRE = 0;
	const DEPART_COMPTOIR = 0;

	/* Attaque */
	// Pourcentage max de diminution de l'attaque
	const ATTAQUE_ALEATOIRE_MAX = 15;
	const ATTAQUE_PILLAGE_MIN = 5;
	const ATTAQUE_PILLAGE_MAX = 12;

	/*
	 * Dans la suite du jeu
	 */

	/* Niveau maximum des batiments */
	/* Attention : les niveaux maximums sont à manipuler avec précaution :
	Il risque d'être impossible d'améliorer le dépôt car le coût sera supérieur à la capacité.
	Autrement, il faut revoir les formules de production, dans le tableur. */
	const MAX_SCIERIE = 30; /* MAX : 30 */
	const MAX_DEPOT = 30; /* MAX : 30 */
	const MAX_CHAMPS = 30; /* MAX : 30 */
	const MAX_ENTREPOT = 30; /* MAX : 30 */

	/* Coûts et gains des batiments */
	const COUT_POULAILLER_CONSTRUCTION = 500; // Bois
	const COUT_COMPTOIR_CONSTRUCTION = 15500; // Bois
	const COUT_COMPTOIR_ECHANGE = 15000; // Bois
	const GAIN_COMPTOIR = 1; // Or

	/*
	 |------------------------------
	 | Options et affichage du jeu
	 |------------------------------
	 */

	/* Login */
	const LONGUEUR_MIN_PSEUDO = 3;
	const LONGUEUR_MAX_PSEUDO = 20; /* Max Database : 32 */
	const LONGUEUR_MIN_PASSWORD = 5;
	const LONGUEUR_MAX_PASSWORD = 30;

	const SECURITY_INSCRIPTION_MAX = 3;

	/* Classement */
	const NB_JOUEUR_PAGE = 100;

	/* Rapport */
	const NB_RAPPORT = 10;

	/*
	 |------------------------------
	 | Calculs et formules
	 |------------------------------
	 */

	/* --- BOIS --- */

	/*
	 * Calcul de la production de bois en seconde en fonction du niveau de la scierie
	 *
	 * Formule : (200 + 200 * log10(lvlScierie / 2) + 10 * lvlScierie) / 3600
	 * [ancienne] ((2^lvlScierie / 2) * 100) / 3600
	 */
	public static function boisSeconde($niveauScierie) {
		if ($niveauScierie == 0) {
			return (50 / 3600.0);
		} else {
			return ((200 + (200 * log10($niveauScierie / 2)) + (10 * $niveauScierie)) / 3600.0);
		}
	}

	/*
	 * Calcul du maximum de bois possible en fonction du niveau du depôt
	 *
	 * Formule : lvlDepot * 500 + 500
	 * [ancienne] lvlDepot * 1000 + 500
	 */
	public static function boisMaximum($niveauDepot) {
		return ($niveauDepot * 500 + 500);
	}


	/*
	 * Calcul le prochain niveau possible de la scierie en fonction du niveau actuel
	 */
	public static function prochainNiveauScierie($niveauScierieActuel) {
		if ($niveauScierieActuel + 1 <= self::MAX_SCIERIE) {
			return $niveauScierieActuel + 1;
		} else {
			return $niveauScierieActuel;
		}
	}

	/*
	 * Calcul en fonction du niveau souhaité de la scierie, le cout de construction pour construire ce niveau
	 *
	 * Formule : 100 + 12 * lvlScierie^2 + 50 * lvlScierie
	 * [ancienne] si niveau 1 alors 100 sinon 300 fois le niveau actuel
	 */
	public static function coutScierie($niveauScierie) {
		return (100 + (12 * pow($niveauScierie, 2)) + (50 * $niveauScierie));
	}


	/*
	 * Calcul le prochain niveau possible du depôt en fonction du niveau actuel
	 */
	public static function prochainNiveauDepot($niveauDepotActuel) {
		if ($niveauDepotActuel + 1 <= self::MAX_DEPOT) {
			return $niveauDepotActuel + 1;
		} else {
			return $niveauDepotActuel;
		}
	}

	/*
	 * Calcul en fonction du niveau souhaité du depôt, le cout de construction pour construire ce niveau
	 *
	 * Formule : 100 + 12 * lvlDepot^2 + 100 * lvlDepot
	 * [ancienne] si niveau 1 alors 300 sinon 500 fois le niveau actuel
	 */
	public static function coutDepot($niveauDepot) {
		return (100 + (12 * pow($niveauDepot, 2)) + (100 * $niveauDepot));
	}

	/* --- GRAINE --- */

	/*
	 * Calcul de la production de graine en seconde en fonction du niveau du champs
	 *
	 * Formule : (41 + 25 * log10(lvlChamps / 3) + 1.2 * lvlChamps) / 3600.0
	 * [ancienne] (lvlChamps * 50) / 3600
	*/
	public static function graineSeconde($niveauChamps) {
		if ($niveauChamps == 0) {
			return 0;
		} else {
			return ((41 + (25 * log10($niveauChamps / 3)) + (1.2 * $niveauChamps)) / 3600.0);
		}
	}

	/*
	 * Calcul du maximum de graine possible en fonction du niveau de l'entrepot
	 *
	 * Formule : lvlEntrepot * 50 + 50
	 * [ancienne] lvlEntrepot * 100 + 50
	 */
	public static function graineMaximum($niveauEntrepot) {
		return ($niveauEntrepot * 50 + 50);
	}


	/*
	 * Calcul le prochain niveau possible des champs en fonction du niveau actuel
	 */
	public static function prochainNiveauChamps($niveauChampsActuel) {
		if ($niveauChampsActuel + 1 <= self::MAX_CHAMPS) {
			return $niveauChampsActuel + 1;
		} else {
			return $niveauChampsActuel;
		}
	}

	/*
	 * Calcul en fonction du niveau souhaité des champs, le cout de construction pour construire ce niveau
	 *
	 * Formule : 440 + 13 * lvlChamps^2 + 50 * lvlChamps
	 * [ancienne] si niveau 1 alors 550 sinon 1050 fois le niveau actuel
	 */
	public static function coutChamps($niveauChamps) {
		return (440 + (13 * pow($niveauChamps, 2)) + (50 * $niveauChamps));
	}


	/*
	 * Calcul le prochain niveau possible de l'entrepôt en fonction du niveau actuel
	 */
	public static function prochainNiveauEntrepot($niveauEntrepotActuel) {
		if ($niveauEntrepotActuel + 1 <= self::MAX_ENTREPOT) {
			return $niveauEntrepotActuel + 1;
		} else {
			return $niveauEntrepotActuel;
		}
	}

	/*
	 * Calcul en fonction du niveau souhaité de l'entrêpôt, le cout de construction pour construire ce niveau
	 *
	 * Formule : 500 + 13 * lvlEntrepot^2 + 100 * lvlEntrepot
	 * [ancienne] si niveau 1 alors 2000 sinon 3000 fois le niveau actuel
	 */
	public static function coutEntrepot($niveauEntrepot) {
		return (500 + (13 * pow($niveauEntrepot, 2)) + (100 * $niveauEntrepot));
	}

}