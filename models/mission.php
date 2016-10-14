<?php

require_once('poulet.php');
require_once('utils/tools.php');

/*
 |------------------------------
 | 		Classe Mission
 | Liste des missions et des actions avec ces dernières.
 |------------------------------
 */

class Mission {

	/*
	 |------------------------------
	 | Liste des missions
	 |------------------------------
	 */

	/*
	 * Liste des missions avec leur contenu respectif.
	 * Indice de tableau débute à 1.
	 */
	const MISSION = [
		/* - Missions spatiales - */
		1 => [
			'id' => 1,
			'titre' => 'Lune',
			'description' => 'Embarquer des poulets dans une fusée lunaire, envoyée à 370 300 km de la Terre.',
			'bouton' => 'Envoyer des poulets sur la Lune',
			'couleur' => 'violet',
			'fonction' => 'missionLune',
		],
		[
			'id' => 2,
			'titre' => 'Mars',
			'description' => 'Embarquer des poulets dans une fusée spatiale à destination de la planète rouge.',
			'bouton' => 'Envoyer des poulets sur Mars',
			'couleur' => 'orange',
			'fonction' => 'missionMars',
		],
		[
			'id' => 3,
			'titre' => 'Soleil',
			'description' => 'Embarquer des poulets dans une fusée spatiale en direction du Soleil.',
			'bouton' => 'Envoyer des poulets vers le Soleil',
			'couleur' => 'jaune',
			'fonction' => 'missionSoleil',
		],
		/* - Missions maritimes - */
		[
			'id' => 4,
			'titre' => 'Atlantide',
			'description' => 'Envoyer des poulets à la recherche de l\'île engloutie.',
			'bouton' => 'Envoyer des poulets chercher l\'Atlantide',
			'couleur' => 'bleu',
			'fonction' => 'missionAtlantide',
		],
	];

	/*
	 |------------------------------
	 | Contenu des missions
	 |------------------------------
	 */

	/*
	 * 1 - Mission Lune
	 */
	private static function missionLune($poulets) {
		/* Initialisation des variables de retour */
		$departPoulets = 0;
		$forceAttaque = 0;
		foreach ($poulets as $poulet) {
			$departPoulets += $poulet['nombre'];
			$forceAttaque += ($poulet['attaque'] * $poulet['nombre']);
		}
		$arriveePoulets = $departPoulets;
		$message = '';

		/* Evenements */
		$evenement = mt_rand(1, 100);
		if($evenement <= 20) { // 20% : Ballade et retour
			$message .= 'Une fois descendus de la fusée, vos poulets se sont émerveillés du paysage lunaire.<br><br>';
			$message .= 'Ils entamèrent alors une petite balade en apesanteur.<br><br>';
			$message .= 'Le temps passa et ils rentrèrent bredouilles, mais contents de leur promenade.<br><br>';
		} elseif ($evenement <= 40) {	// 20 % : Perdu lune
			$message .= 'Après être descendus de la fusée, vos poulets se sont émerveillés du paysage lunaire.<br><br>';
			$message .= 'Ils entamèrent alors une petite balade en apesanteur.<br><br>';
			$message .= 'Malheureusement, leur goût pour l\'aventure les perdit dans une crevasse.<br><br>';

			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} elseif ($evenement <= 55) {	// 15 % : Perdu espace
			$message .= 'La fusée est partie dans l\'espace, mais sa trajectoire était mal programmée.<br><br>';
			$message .= 'Elle dévia de l\'horizon lunaire et se perdit dans l\'espace, avec tous vos poulets à l\'intérieur.<br><br>';

			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} elseif ($evenement <= 65) {	// 10 % : Trouvé précédante expédition
			$message .= 'Une fois descendus de la fusée, vos poulets se sont émerveillés du paysage lunaire.<br><br>';
			$message .= 'Ils entamèrent alors une reconnaissance du secteur.<br><br>';
			$message .= 'C\'est alors qu\'ils trouvèrent d\'anciens poulets, égarés et oubliés lors d\'une précédente mission.<br><br>';

			$totalPoulet = 0;
			foreach ($poulets as $poulet) {
				if($poulet['nombre'] > 0) { // Pour chaque catégorie de poulets envoyés
					$nbPoulet = 2 + floor((mt_rand(1, 2)  * $poulet['nombre']) / 5);

					$nouvelleQuantite = $poulet['quantite'] + $nbPoulet;
					Poulet::setQuantite(unserialize($_SESSION['user'])->id, $poulet['id'], $nouvelleQuantite);

					$arriveePoulets += $nbPoulet;
					$totalPoulet += $nbPoulet;
				}
			}

			$message .= 'Vous revenez avec <strong>' . $totalPoulet . ' poulets</strong> supplémentaires.<br><br>';
		} elseif ($evenement <= 75) {	// 10 %	: Fusée sans poulets
			$message .= 'La fusée est partie dans l\'espace, mais les poulets ont oublié de monter dedans.<br><br>';
		} elseif ($evenement <= 90) {	// 15 % : Sac de graine
			$graine = mt_rand(1, 3) * $forceAttaque;

			$message .= 'Une fois descendus de la fusée, vos poulets se sont emerveillés du paysage lunaire.<br><br>';
			$message .= 'Ils entamèrent alors une recherche appronfondie du périmètre, bien décidés à trouver quelque chose à ramener.<br><br>';
			$message .= 'C\'est alors qu\'ils trouvèrent une fusée abandonnée lors d\'une précédente mission.<br><br>';
			$message .= 'Après inspection du contenu de cette dernière, un sac de <strong>' . Tools::formatNumber($graine) . ' graines</strong> a été trouvé.<br><br>';

			$nouvelleRessource = array(
				'graine' => $_SESSION['ressource']['graine'] + $graine
			);
			Ressource::modify(unserialize($_SESSION['user'])->id, $nouvelleRessource);
		} else { // 10 % : Echec au lancement
			$message .= 'Echec au lancement de la fusée :<br>un réacteur commença à s\'enflammer et empêcha la fusée de décoller.<br><br>';
		}

		$rapport = [
			'message' => $message,
			'arriveePoulets' => $arriveePoulets,
		];

		return $rapport;
	}

	/*
	 * 2 - Mission Mars
	 */
	private static function missionMars($poulets) {
		/* Initialisation des variables de retour */
		$departPoulets = 0;
		$forceAttaque = 0;
		foreach ($poulets as $poulet) {
			$departPoulets += $poulet['nombre'];
			$forceAttaque += $poulet['attaque'];
		}
		$arriveePoulets = $departPoulets;
		$message = '';

		/* Evenements */
		$evenement = mt_rand(1, 100);
		if($evenement <= 20) { // 20% : Ballade bredouille
			$message .= 'Le voyage en direction de la planète Mars fut particulièrement long.<br><br>';
			$message .= 'Une fois descendus de la fusée, vos poulets se sont emerveillés du paysage martien.<br><br>';
			$message .= 'Ils entamèrent alors une petite balade sur la planète rouge.<br><br>';
			$message .= 'Le temps passa et ils rentrèrent bredouilles, mais contents de leur promenade.<br><br>';
		} elseif ($evenement <= 40) {	// 20 % : Discussion Curiosity
			$message .= 'Le trajet à destination de la planète Mars dura longtemps.<br><br>';
			$message .= 'Après être descendus de la fusée, vos poulets partirent en découverte.<br><br>';
			$message .= 'Lors de leur exploration, ils trouvèrent un astromobile nommé <i>Curiosity</i>.<br><br>';
			$message .= 'Ils entamèrent alors une discussion passionnante avec le robot américain. Ce dernier était doté d\'une grande curiosité...<br><br>';
			$message .= 'Au bout d\'un certain temps, ils prirent le chemin du retour, et saluèrent le rover continuant sa tâche sur mars.<br><br>';
		} elseif ($evenement <= 60) {	// 20 % : Graines des martiens
			$graine = mt_rand(1, 4) * $forceAttaque;

			$message .= 'Le voyage à destination de la planète Mars fut assez long.<br><br>';
			$message .= 'Après être descendus de la fusée, vos poulets sont partis en reconnaissance.<br><br>';
			$message .= 'Lors de leur exploration, ils se sont retrouvés nez à nez avec des martiens.<br><br>';
			$message .= 'Ces derniers, heureux d\'avoir de la visite, leur offrirent un sac de graines en leur souhaitant bon retour.<br><br>';
			$message .= 'Le sac contenait <strong>' . Tools::formatNumber($graine) . ' graines</strong>.<br><br>';

			$nouvelleRessource = array(
				'graine' => $_SESSION['ressource']['graine'] + $graine
			);
			Ressource::modify(unserialize($_SESSION['user'])->id, $nouvelleRessource);
		} elseif ($evenement <= 70) {	// 10 % : Pris le contrôle, perdu dans l'espace
			$message .= 'Le voyage à destination de la planète Mars débuta comme prévu.<br><br>';
			$message .= 'Mais tout à coup la fusée dévia de sa trajectoire.<br><br>';
			$message .= 'Elle prit la direction d\'une autre galaxie avant de perdre le contact.<br><br>';
			$message .= 'Mais qui a pu détourner la fusée et voler vos poulets ?...<br><br>';

			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} elseif ($evenement <= 80) {	// 10 %	: Crash avec sonde
			$message .= 'Le voyage en direction de la planète Mars débuta comme prévu.<br><br>';
			$message .= 'Lors du trajet, un radar de la fusée indiqua l\'approche imminente d\'une collision.<br><br>';
			$message .= 'Ce ne fut qu\'au dernier moment que les poulets aperçurent l\'objet en approche :<br>';
			$message .= 'c\'était une sonde spatiale portant l\'inscription "Mars Express" sur le côté...<br><br>';
			$message .= 'L\'impact fut si violent que la fusée explosa en mille morceaux.<br><br>';

			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} elseif ($evenement <= 90) {	// 10 % : Martiens hostiles poulets grillés
			$message .= 'Le trajet à destination de la planète Mars fut particulièrement long.<br><br>';
			$message .= 'Après être descendus de la fusée, vos poulets sont partis explorer les environs.<br><br>';
			$message .= 'Lors de leur excursion, ils découvrirent un camp de martiens :<br>';
			$message .= 'ces derniers adpotèrent une attitude hostile, et malgré le retour précipité de vos poulets en direction de la fusée, les martiens les rattrapèrent.<br><br>';
			$message .= 'Ce soir là, au menu du dîner des martiens, on pouvait lire "Poulets grillés à la broche".<br><br>';
			
			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} else { // 10 % : Echec au lancement
			$message .= 'Echec au lancement de la fusée :<br>un réacteur commença à s\'enflammer et empêcha la fusée de décoller.<br><br>';
		}

		$rapport = [
			'message' => $message,
			'arriveePoulets' => $arriveePoulets,
		];

		return $rapport;
	}

	/*
	 * 3 - Mission Soleil
	 */
	private static function missionSoleil($poulets) {
		/* Initialisation des variables de retour */
		$arriveePoulets = 0;
		$message = '';

		$message .= 'La fusée est partie avec vos poulets à bord en direction du Soleil.<br><br>';
		$message .= 'La chaleur à l\'intérieur de cette dernière s\'est très rapidement fait ressentir.<br><br>';
		$message .= 'Une odeur de poulet grillé fut le dernier signal envoyé par la fusée.<br><br>';
		$message .= 'Malheureusement, la communication avec cette dernière a ensuite été interrompue...<br><br>';

		self::enleverTousPoulets($poulets);

		$rapport = [
			'message' => $message,
			'arriveePoulets' => $arriveePoulets,
		];

		return $rapport;
	}
	
	/*
	 * 4 - Mission Atlantide
	 */
	private static function missionAtlantide($poulets) {
		/* Initialisation des variables de retour */
		$departPoulets = 0;
		$forceAttaque = 0;
		foreach ($poulets as $poulet) {
			$departPoulets += $poulet['nombre'];
			$forceAttaque += $poulet['attaque'];
		}
		$arriveePoulets = $departPoulets;
		$message = '';

		/* Evenements */
		$evenement = mt_rand(1, 100);
		if($evenement <= 50) { // 50% : Recherches vaines
			$message .= 'Les poulets embarquèrent dans le sous-marin en direction de l\'Atlantide.<br><br>';
			$message .= 'Ils entamèrent leur recherche de la cité perdue en explorant les fonds océaniques.<br><br>';
			$message .= 'Les recherches perdurèrent des heures.<br><br>';
			$message .= 'A bout de force, vos poulets rentrèrent épuisés sans avoir trouvé le moindre vestige.<br><br>';
			$message .= 'Peut-être découvriront-ils l\'Atlantide une prochaine fois ?<br><br>';
		} elseif ($evenement <= 63) {	// 13 % : Poulet perdus
			$message .= 'Les poulets embarquèrent dans le sous-marin en direction de l\'Atlantide.<br><br>';
			$message .= 'Ils entamèrent leur recherche de la cité perdue en explorant les fonds océaniques.<br><br>';
			$message .= 'Malheureusement, le sous-marin percuta un immense rocher et la coque se fendit.<br><br>';
			$message .= 'Tous vos poulets finirent noyés dans les profondeurs de l\'océan.<br><br>';

			self::enleverTousPoulets($poulets);
			$arriveePoulets = 0;
		} elseif ($evenement <= 65) {	// 2 % : Atlantide trouvée (graines et poulets)
			$graine = mt_rand(2, 5) * $forceAttaque;

			$message .= 'Les poulets embarquèrent dans le sous-marin en direction de l\'Atlantide.<br><br>';
			$message .= 'Ils entamèrent leur recherche de la cité perdue en explorant les fonds océaniques.<br><br>';
			$message .= 'Après plusieurs heures d\'observation laborieuse, ils découvrirent une entrée.<br><br>';
			$message .= 'Il s\'agissait de l\'<strong>Atlantide</strong> !<br><br>';
			$message .= 'En fouillant les lieux, vos poulets trouvèrent le trésor de l\'Atlantide :<br>';
			$message .= 'c\'était un coffre contenant <strong>' . Tools::formatNumber($graine) . ' graines</strong>.<br><br>';
			$message .= 'Les poulets étaient très joyeux et le retour se fit sans encombre.<br><br>';

			$nouvelleRessource = array(
				'graine' => $_SESSION['ressource']['graine'] + $graine
			);
			Ressource::modify(unserialize($_SESSION['user'])->id, $nouvelleRessource);
		} else { // 35 % : Echec au lancement
			$message .= 'Echec à l\'embarquement du sous-marin :<br>l\'hélice était accrochée dans les algues.<br><br>';
		}

		$rapport = [
			'message' => $message,
			'arriveePoulets' => $arriveePoulets,
		];

		return $rapport;
	}

	/*
	 |------------------------------
	 | Fonctions statiques
	 |------------------------------
	 */

	/*
	 * Lance la mission passée en paramètre, qui doit être renseignée dans le contenu des missions.
	 */
	public static function lancerMission($mission, $poulets) {
		mt_srand(time() + 193755782);

		/* Déclaration des variables de retour du rapport */ 
		$rapport = array();
		$rapport['date'] = '';
		$rapport['heure'] = '';
		$rapport['departPoulets'] = 0;
		$rapport['arriveePoulets'] = 0;
		$rapport['message'] = 'Mission échouée.';

		/* Initialisation des variables de retour du rapport */
		$rapport['date'] = date('j/m/Y');
		$rapport['heure'] = date('H:i');
		foreach ($poulets as $poulet) {
			$rapport['departPoulets'] += $poulet['nombre'];
		}
		$rapport['arriveePoulets'] = $rapport['departPoulets'];

		/* --- Lancement de la mission --- */
		$nomMission = 'self::' . self::MISSION[$mission['id']]['fonction'];
		$result = call_user_func_array($nomMission, array($poulets));

		/* Génération du rapport à retourner */
		if(isset($result['arriveePoulets'])) {
			$rapport['arriveePoulets'] = $result['arriveePoulets'];
		}
		if(isset($result['message']) && !empty($result['message'])) {
			$rapport['message'] = $result['message'];
		}

		return $rapport;
	}

	/*
	 * Enlève tous les poulets partis en mission de l'armée du joueur courant, à partir du tableau de poulets.
	 * Nécéssite :	$poulets['quantite'],
	 *				$poulets['nombre'],
	 *				$poulets['id'],
	 *				$_SESSION['user']
	 */
	public static function enleverTousPoulets($poulets) {
		foreach ($poulets as $poulet) {
			$nouvelleQuantite = $poulet['quantite'] - $poulet['nombre'];
			if($nouvelleQuantite < 0) { // Vérification si pas nouvelle quantité négative
				$nouvelleQuantite = 0;
			}
			Poulet::setQuantite(unserialize($_SESSION['user'])->id, $poulet['id'], $nouvelleQuantite);
		}
	}

}