<?php

require_once('poulet.php');
require_once('user.php');
require_once('utils/tools.php');
require_once('params/rules.php');

/*
 |------------------------------
 | 		Classe Attaque
 | Gestion des combats entre les joueurs.
 |------------------------------
 */

class Attaque {

	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Lance l'attaque entre les joueurs $idAttaquant et $idDefenseur passés en paramètre.
	 * Les poulets recrutés dans formulaire d'attaque sont passés par $pouletsAttaquant.
	 * NON UTILISE
	 */
	public static function old_lancerAttaque($idAttaquant, $idDefenseur, $pouletsAttaquant) {
		srand(time());

		/* Déclaration des variables de retour du rapport */ 
		$rapport = array();
		$rapport['date'] = '';
		$rapport['heure'] = '';
		$rapport['departPoulets'] = 0;
		$rapport['arriveePoulets'] = 0;
		$rapport['message'] = '';
		$rapport['pouletsAttaquant'] = $pouletsAttaquant;

		/* Initialisation des variables de retour du rapport */
		$rapport['date'] = date('j/m/Y');
		$rapport['heure'] = date('H:i');
		foreach ($pouletsAttaquant as $poulet) {
			$rapport['departPoulets'] += $poulet['nombre'];
		}
		$rapport['arriveePoulets'] = $rapport['departPoulets'];

		/* --- Lancement du combat --- */
		$continue = true;
		$armeeDefenseur = Poulet::getArmyWithPoulet($idDefenseur);
		// Si le défenseur n'a pas d'armée
		if (empty($armeeDefenseur)) {
			$rapport['message'] = 'Pas de défense adverse.';
			$continue = false;
		}

		// Si l'armée du défenseur est vide
		$nombrePoulets = 0;
		foreach ($armeeDefenseur as $poulet) {
			$nombrePoulets += $poulet['quantite'];
		}
		if ($nombrePoulets == 0) {
			$rapport['message'] = 'Pas de défense adverse.';
			$continue = false;
		}

		if ($continue == true) {
			// On retire à l'attaquant ses poulets envoyés au combat
			$attaquantArmee = Poulet::getArmyWithPoulet($idAttaquant);
			foreach ($pouletsAttaquant as $poulet) {
				// S'il y a des poulets envoyés au combat, donc a retirer
				if (!empty($poulet['nombre'])) {
					$id = array_search($poulet['id'], array_column($attaquantArmee, 'id'));
					$quantite = $attaquantArmee[$id]['quantite'];
					Poulet::setQuantite($idAttaquant, $poulet['id'], $quantite - $poulet['nombre']);
				}
			}

			// Type de poulet qui défend :
			//		- Plus nombreux (quantité possédée défenseur)
			//		- Plus fort (points défense elevés)
			$typePouletDefense = rand(1,2);

			$nombre = 0;
			$pouletIdQuantite = 0;
			$defense = 0;
			$pouletIdDefense = 0;
			foreach ($armeeDefenseur as $poulet) {
				if ($poulet['quantite'] > 0) {
					if ($poulet['quantite'] > $nombre) {
						$nombre = $poulet['quantite'];
						$pouletIdQuantite = array_search($poulet['id'], array_column($armeeDefenseur, 'id'));
					}
					if ($poulet['defense'] > $defense) {
						$defense = $poulet['defense'];
						$pouletIdDefense = array_search($poulet['id'], array_column($armeeDefenseur, 'id'));
					}
				}
			}

			$pouletDefense = 0;
			if ($typePouletDefense == 1) {
				// Plus fort
				$pouletDefense = $pouletIdDefense;
			} else {
				// Plus nombreux
				$pouletDefense = $pouletIdQuantite;
			}

			// Type de poulet qui attaque :
			//		- Plus nombreux (quantité possédée défenseur)
			//		- Plus fort (points défense elevés)
			$typePouletAttaque = rand(1,2);

			$nombre = 0;
			$pouletIdQuantite = 0;
			$attaque = 0;
			$pouletIdDefense = 0;
			foreach ($pouletsAttaquant as $poulet) {
				if ($poulet['nombre'] > 0) {
					if ($poulet['nombre'] > $nombre) {
						$nombre = $poulet['nombre'];
						$pouletIdQuantite = array_search($poulet['id'], array_column($pouletsAttaquant, 'id'));
					}
					if ($poulet['attaque'] > $attaque) {
						$attaque = $poulet['attaque'];
						$pouletIdDefense = array_search($poulet['id'], array_column($pouletsAttaquant, 'id'));
					}
				}
			}

			$pouletAttaque = 0;
			if ($typePouletAttaque == 1) {
				// Plus fort
				$pouletAttaque = $pouletIdDefense;
			} else {
				// Plus nombreux
				$pouletAttaque = $pouletIdQuantite;
			}

			/* - Combat entre le groupe de poulet attaquant et défenseur - */
			$attaquantAttaque = $pouletsAttaquant[$pouletAttaque]['nombre'] * $pouletsAttaquant[$pouletAttaque]['attaque'];
			$attaquantDefense = $pouletsAttaquant[$pouletAttaque]['nombre'] * $pouletsAttaquant[$pouletAttaque]['defense'];
			$defenseurAttaque = $armeeDefenseur[$pouletDefense]['quantite'] * $armeeDefenseur[$pouletDefense]['attaque'];
			$defenseurDefense = $armeeDefenseur[$pouletDefense]['quantite'] * $armeeDefenseur[$pouletDefense]['defense'];

			/* Attaquant - Attaque */
			$attaque = rand(1, $defenseurDefense);
			if ($attaque <= $attaquantAttaque) { // Attaque réussie
				// Combien de poulets défenseur vont perdre
				$nombrePouletAttaque = rand(1, ($attaquantAttaque / $defenseurDefense) * $armeeDefenseur[$pouletDefense]['quantite']);
				$armeeDefenseur[$pouletDefense]['quantite'] -= $nombrePouletAttaque;
				if ($armeeDefenseur[$pouletDefense]['quantite'] < 0) {
					$armeeDefenseur[$pouletDefense]['quantite'] = 0;
				}
			}
			/* Défenseur - Défense (contre-attaque) */
			$contreAttaque = rand(1, $attaquantDefense);
			if ($contreAttaque <= $defenseurAttaque) { // Contre attaque réussie
				// Combien de poulets attaquants vont perdre
				$nombrePouletContreAttaque = rand(1, ($defenseurAttaque / $attaquantDefense) * $pouletsAttaquant[$pouletAttaque]['nombre']);
				$pouletsAttaquant[$pouletAttaque]['nombre'] -= $nombrePouletContreAttaque;
				if ($pouletsAttaquant[$pouletAttaque]['nombre'] < 0) {
					$pouletsAttaquant[$pouletAttaque]['nombre'] = 0;
				}
			}

			/* Mise à jour des quantite de poulets */
			$rapport['arriveePoulets'] = 0;
			$attaquantArmee = Poulet::getArmyWithPoulet($idAttaquant);
			foreach ($pouletsAttaquant as $poulet) {
				if (!empty($poulet['nombre'])) {
					$id = array_search($poulet['id'], array_column($attaquantArmee, 'id'));
					$quantite = $attaquantArmee[$id]['quantite'];
					Poulet::setQuantite($idAttaquant, $poulet['id'], $quantite + $poulet['nombre']);

					$rapport['arriveePoulets'] += $poulet['nombre'];
				}
			}
			foreach ($armeeDefenseur as $poulet) {
				Poulet::setQuantite($idDefenseur, $poulet['id'], $poulet['quantite']);
			}

			// Nombre de poulet perdu au combat dans le rapport
			$rapport['pouletsAttaquant'] = $pouletsAttaquant;

			/* Génération du rapport à retourner */
			$rapport['message'] .= 'Ce fut un combat sanglant. De nombreux poulets perdirent des plumes. Les crètes volairent en éclats.';
		}

		// S'il n'y a pas de message de retour, c'est que l'attaque a échoué
		if (empty($rapport['message'])) {
			$rapport['message'] = 'Attaque échouée.';
		}

		return $rapport;
	}

	/*
	 * Lance l'attaque entre les joueurs $idAttaquant et $idDefenseur passés en paramètre.
	 * Les poulets recrutés dans formulaire d'attaque sont passés par $pouletsAttaquant.
	 * Nouvel algorithme de août 2016.
	 */
	public static function lancerAttaque($idAttaquant, $idDefenseur, $pouletsAttaquant) {
		/* - Initialisation - */

		srand(time());

		/* Déclaration des variables de retour du rapport */ 
		$rapport = array();
		$rapport['date'] = '';
		$rapport['heure'] = '';
		$rapport['departPoulets'] = 0;
		$rapport['arriveePoulets'] = 0;
		$rapport['message'] = '';
		$rapport['pouletsAttaquant'] = $pouletsAttaquant;
		$rapport['vainqueur'] = '?';

		/* Initialisation des variables de retour du rapport */
		$rapport['date'] = date('j/m/Y');
		$rapport['heure'] = date('H:i');
		foreach ($pouletsAttaquant as $poulet) {
			$rapport['departPoulets'] += $poulet['nombre'];
		}
		$rapport['arriveePoulets'] = $rapport['departPoulets'];

		/* Vérifications avant le combat */

		// Si l'attaquant n'a pas envoyé de poulet (vérification déjà existante dans le formulaire)
		if (empty($pouletsAttaquant)) {
			$rapport['message'] = 'Pas de poulets à envoyer au combat.';
			return $rapport;
		}

		$pouletsDefenseur = Poulet::getArmyWithPoulet($idDefenseur);
		// Si le défenseur n'a pas d'armée
		if (empty($pouletsDefenseur)) {
			$rapport['message'] = 'Pas de défense adverse.';
			$rapport['vainqueur'] = User::getInformations($idAttaquant)['pseudo'];
			return $rapport;
		}

		// Si l'armée du défenseur est vide
		$nombrePouletsDefenseur = 0;
		foreach ($pouletsDefenseur as $poulet) {
			if (!empty($poulet['quantite'])) {
				$nombrePouletsDefenseur += $poulet['quantite'];
			}
		}
		if ($nombrePouletsDefenseur == 0) {
			$rapport['message'] = 'Pas de défense adverse.';
			$rapport['vainqueur'] = User::getInformations($idAttaquant)['pseudo'];
			return $rapport;
		}

		/* - Combat - */

		// Calcul de la puissance d'attaque de l'attaquant (attaque)
		$puissanceAttaqueAttaquant = 0;
		foreach ($pouletsAttaquant as $poulet) {
			$puissanceAttaqueAttaquant += ($poulet['nombre'] * $poulet['attaque']);
		}

		// Diminution aléatoire de la puissance d'attaque
		$aleatoireDiminutionAttaque = rand(0, Rules::ATTAQUE_ALEATOIRE_MAX);
		$puissanceAttaqueAttaquant -= $puissanceAttaqueAttaquant * ($aleatoireDiminutionAttaque / 100);
		$puissanceAttaqueAttaquant = intval(round($puissanceAttaqueAttaquant));

		// Calcul de la puissance d'attaque du défenseur (contre-attaque)
		$puissanceAttaqueDefenseur = 0;
		foreach ($pouletsDefenseur as $poulet) {
			$puissanceAttaqueDefenseur += ($poulet['quantite'] * $poulet['attaque']);
		}

		// Initialisation des compteurs de poulets morts (détermine le vainqueur)
		$nombrePouletsMortsAttaquant = 0;
		$nombrePouletsMortsDefenseur = 0;

		/* Attaque (Attaquant -> Défenseur) */

		// Calcul de la puissance d'attaque répartie pour chaque type de poulets du défenseur
		$nombreTypePouletDefenseur = 0;
		foreach ($pouletsDefenseur as $poulet) {
			if ($poulet['quantite'] > 0) {
				$nombreTypePouletDefenseur++;
			}
		}
		$puissanceAttaqueParPoulet = $puissanceAttaqueAttaquant / $nombreTypePouletDefenseur;
		
		// Le nombre de type de poulet restant à attaquer (initialisé au nombre de type de poulets qui vont être attaqués)
		$nombreTypePouletRestantDefenseur = $nombreTypePouletDefenseur;

		// Nouveau tableau des poulets défenseur, pour la mise à jour finale
		$newPouletsDefenseur = $pouletsDefenseur;

		// Tri du tableau de poulets du défenseur dans l'ordre croissant de la défense de chaque poulet
		for ($i = 1; $i < count($newPouletsDefenseur); $i++) {
			if (isset($newPouletsDefenseur[$i])) {
				if ($newPouletsDefenseur[$i]['defense'] < $newPouletsDefenseur[$i - 1]['defense']) {
					$pouletTemporaire = $newPouletsDefenseur[$i - 1];
					$newPouletsDefenseur[$i - 1] = $newPouletsDefenseur[$i];
					$newPouletsDefenseur[$i] = $pouletTemporaire;
					$i = 0;
				} else {
					$i++;
				}
			}
		}

		// Nombre de poulets morts au combat pour chaque type de poulet
		foreach ($newPouletsDefenseur as $key => $value) {
			// S'il y a au moins un poulet à attaquer
			if ($newPouletsDefenseur[$key]['quantite'] > 0) {
				// Nouvelle attaque, donc un type de poulet en moins restant à attaquer
				$nombreTypePouletRestantDefenseur--;
				// Nombre de poulets tués dans cette attaque
				$nombrePouletsMort = $puissanceAttaqueParPoulet / $newPouletsDefenseur[$key]['defense'];

				// Calcul du report d'attaque
				$puissancePouletDefense = $poulet['quantite'] * $newPouletsDefenseur[$key]['defense'];
				// S'il reste de la puissance d'attaque et des poulets à attaquer pour la redistribuer 
				if ($puissanceAttaqueParPoulet > $puissancePouletDefense && $nombreTypePouletRestantDefenseur > 0) {
					// Partage de la puissance d'attaque restante pour chaque poulet restant
					$puissanceAttaqueParPoulet += ($puissanceAttaqueParPoulet - $puissancePouletDefense) / $nombreTypePouletRestantDefenseur;
				}

				// Retrait des poulets morts (0 poulet minimum)
				if ($newPouletsDefenseur[$key]['quantite'] - $nombrePouletsMort >= 0) {
					$newPouletsDefenseur[$key]['quantite'] -= $nombrePouletsMort;

					$nombrePouletsMortsDefenseur += $nombrePouletsMort;
				} else {
					$newPouletsDefenseur[$key]['quantite'] = 0;

					$nombrePouletsMortsDefenseur += $newPouletsDefenseur[$key]['quantite'];
				}
			}
		}

		/* Contre-attaque (Défenseur -> Attaquant) */

		// Calcul de la puissance d'attaque répartie pour chaque type de poulets de l'attaquant
		$nombreTypePouletAttaquant = 0;
		foreach ($pouletsAttaquant as $poulet) {
			if ($poulet['nombre'] > 0) {
				$nombreTypePouletAttaquant++;
			}
		}
		$puissanceAttaqueParPoulet = $puissanceAttaqueDefenseur / $nombreTypePouletAttaquant;
		
		// Le nombre de type de poulet restant à attaquer (initialisé au nombre de type de poulets qui vont être attaqués)
		$nombreTypePouletRestantAttaquant = $nombreTypePouletAttaquant;

		// Nouveau tableau des poulets défenseur, pour la mise à jour finale
		$newPouletsAttaquant = $pouletsAttaquant;

		// Tri du tableau de poulets de l'attaquant dans l'ordre croissant de la défense de chaque poulet
		for ($i = 1; $i < count($newPouletsAttaquant); $i++) {
			if (isset($newPouletsAttaquant[$i])) {
				if ($newPouletsAttaquant[$i]['defense'] < $newPouletsAttaquant[$i - 1]['defense']) {
					$pouletTemporaire = $newPouletsAttaquant[$i - 1];
					$newPouletsAttaquant[$i - 1] = $newPouletsAttaquant[$i];
					$newPouletsAttaquant[$i] = $pouletTemporaire;
					$i = 0;
				} else {
					$i++;
				}
			}
		}

		// Nombre de poulets morts au combat pour chaque type de poulet
		foreach ($newPouletsAttaquant as $key => $value) {
			// S'il y a au moins un poulet à attaquer
			if ($newPouletsAttaquant[$key]['nombre'] > 0) {
				// Nouvelle attaque, donc un type de poulet en moins restant à attaquer
				$nombreTypePouletRestantAttaquant--;
				// Nombre de poulets tués dans cette attaque
				$nombrePouletsMort = $puissanceAttaqueParPoulet / $newPouletsAttaquant[$key]['defense'];

				// Calcul du report d'attaque
				$puissancePouletDefense = $poulet['nombre'] * $newPouletsAttaquant[$key]['defense'];
				// S'il reste de la puissance d'attaque et des poulets à attaquer pour la redistribuer 
				if ($puissanceAttaqueParPoulet > $puissancePouletDefense && $nombreTypePouletRestantAttaquant > 0) {
					// Partage de la puissance d'attaque restante pour chaque poulet restant
					$puissanceAttaqueParPoulet += ($puissanceAttaqueParPoulet - $puissancePouletDefense) / $nombreTypePouletRestantAttaquant;
				}

				// Retrait des poulets morts (0 poulet minimum)
				if ($newPouletsAttaquant[$key]['nombre'] - $nombrePouletsMort >= 0) {
					$newPouletsAttaquant[$key]['nombre'] -= $nombrePouletsMort;

					$nombrePouletsMortsAttaquant += $nombrePouletsMort;
				} else {
					$newPouletsAttaquant[$key]['nombre'] = 0;

					$nombrePouletsMortsAttaquant += $newPouletsAttaquant[$key]['nombre'];
				}
			}
		}
		
		/* - Mise à jour des quantités de poulets - */

		/* Attaquant */
		$rapport['arriveePoulets'] = 0;
		foreach ($pouletsAttaquant as $poulet) {
			// Si un poulet a été envoyé
			if (!empty($poulet['nombre'])) {
				$id = array_search($poulet['id'], array_column($newPouletsAttaquant, 'id'));
				$quantite = $poulet['quantite'];
				$perte = $poulet['nombre'] - $newPouletsAttaquant[$id]['nombre'];
				Poulet::setQuantite($idAttaquant, $poulet['id'], $quantite - $perte);

				$rapport['arriveePoulets'] += round($newPouletsAttaquant[$id]['nombre']);
			}
		}

		/* Défenseur */
		foreach ($newPouletsDefenseur as $poulet) {
			Poulet::setQuantite($idDefenseur, $poulet['idPoulet'], $poulet['quantite']);
		}

		// Nombre de poulet perdu au combat dans le rapport
		$rapport['pouletsAttaquant'] = $newPouletsAttaquant;

		/* - Génération du rapport (à retourner) - */
		$rapport['message'] .= 'Ce fut un combat sanglant. De nombreux poulets perdirent des plumes. Les crètes volèrent en éclats.';

		// Détermination du vainqueur
		if ($nombrePouletsMortsAttaquant > $nombrePouletsMortsDefenseur) {
			// Attaquant
			$rapport['vainqueur'] = User::getInformations($idAttaquant)['pseudo'];
		} elseif ($nombrePouletsMortsAttaquant == $nombrePouletsMortsDefenseur) {
			// Egalité
			$rapport['vainqueur'] = 'Pas de vainqueur, les pertes sont identiques.';
		} else {
			// Defenseur
			$rapport['vainqueur'] = User::getInformations($idDefenseur)['pseudo'];
		}

		return $rapport;
	}
}