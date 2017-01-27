-------------------- [ Informations ] --------------------

--- ToDo ---

[/] Générer les rapports de combats => BDD + affichage des 10 derniers.
[/] Faire une page Mon Compte accessible en haut à droite via une icône type "Réglages" (une icône d'écrou) avec changement de mot de passe et possibilité de définir une adresse e-mail pour la récupération de mot de passe oublié.
[ ] Sytème de traduction, principalement en Anglais => Drapeau dans une barre de navigation ou dans les réglages (accès sans être connecté ?)
[ ] Faire le laboratoire (voir idées et améliorations).

--

[ ] Animer encore plus d'images
[ ] Images de bois et graine pour les ressources dans la barre en haut
[ ] Graines : ajouter le nombre de champs à côté du niveau (plus parlant, plus équivoque)

[ ] Petit gif animé de poulets se battant lors du combat ?
[ ] Mission avec temps de découverte : plus on envoie de poulet dans la mission (nombre stocké), ou plus on fait souvent la mission, plus on a de chance de trouver l'objet céleste (idée avec l'Atlantide, trouve des indices, et au final, au bout d'un certain temps, réussissent à découvrir l'Atlantide)
[ ] Ajouter un loto ou une loterie avec ticket et gain chaque jour
[ ] Mission au pôle Nord et pôle Sud (iceberg)
[ ] Error 404 ?
[ ] Ajouter un mode de maintenance

[ ] Bouton déconnexion -> changer pour une icone de déconnexion plutôt de la croix, ou mettre un écran de confirmation
[ ] Logique du besoin de bois pour améliorer les champs : autre nom : moulin ou chariots de transport
[ ] Améliorer : préciser quoi


-- Done --

[X] Système de météo lors d'une attaque indiquant la probabilité aléatoire de diminution de la puissance d'attaque
[X] Terminer l'algorithme de combat avec plusieurs espèces de poulets différentes (groupes) en même temps.
[X] Prefixe des tables de la base de données en une variable globale placée dans chaque FROM ou dans la classe Database
[X] Aléatoire dans l'algorithme de combat : 0.9 chance de réussir pour l'attanque ou de se voir diminuer son attaque globale
[X] Plus de poulet en base de données, avec des attaques et défense différentes et 3 poulets au départ
[X] Terminer toutes les missions, et regarder si les gains apportés sont toujours plausibles
[X] Nécessite bois/graine -> Coute bois/graine
[X] FAQ -> Renommer en aide
[X] Ajouter un favicon
[X] Ajouter les balises méta pour le web mobile iPhone et Android (avec l'image de favicon aux différentes dimensions et bonne qualité)
[X] Ajouter des balises méta pour le référencement
[X] Vérifier l'orthographe de tout le jeu
[X] Format de fin de ligne : LN Unix
[X] Vérifier la couleur de la barre Chrome en web mobile
[X] Couleur des boutons de jeu
[X] Nom de joueur pseudo avec balise img (faille injection)
[X] Rapport quand un seul rapport, vérifier la liste, bon affichage
[X] Envoyer au moins 2 poulets en mission pour le pluriel des textes de mission
[X] Re-calculer les ratios de génération des mines / production pour que le fun soit présent
[X] Ajuster le coût des poulets en fonction de leur nombre de points
[X] Vérification des pseudos => regex
[X] Vérifier section Bug ci-dessous
[X] Enlever les var_dump de l'attaque
[X] Préparer le script SQL

--------------------


--- Bug ---

[ ] Bug recharchement page quand lcicl depuis le home game, sur la liste des liens avec la flèche (redirection javascript)
[ ] Il semblerait qu'il y ait un bug lors de la déconnexion, si une erreur survient juste après (pour reconnecter un autre compte), l'erreur s'affiche parfois directement dans le précédent compte connecté.
[ ] Arnaud attaque Robert 1 Joyeu contre 4 Frêle -> erreur quantité des poulets non insérée en BD dans army car ajouté après

-- Résolus --

[X] CTRL+F puis changement de page -> supprime titre <h2> et image de la page sur PC
		Surtout sur Google Chrome => Voir en bas les bouts de code
	[29 et 30 /06/2016] Changement des id des compteurs en class + suppression des code jQuery de manipulation de page + vérifie si le compteur existe déjà.
	A nécessité un second bout de code pour supprimer du DOM la première page en cache : /* Supprime page cache */ https://github.com/jquery/jquery-mobile/issues/4050

[X] Login : Arnaud';--   -> login.php /* DOIT TESTER SI UTILISATEUR VALIDE */ + Vérifier données entrées (input) des noms : taille max + pas de caractères spéciaux
	[28/06/2016 20:59] Les caractères spéciaux sont laissés autorisé

[X] Changement de page jQuery bug des ressources (la première page chargée reste en fond et est rappelée quand besoin est, au lieu d'être rechargée)
	[27/04/2016 20:47]

[X] Loader jQuery Mobile gif pour les chargements
	[1/10/2016 19:30] Mettre images/ajax-loader.gif dans /views/css/images/ajax-loader.gif

[X] Texte non aligné au centre quand attaque joueur sans poulets
	[2/10/2016] Pas un bug, juste le nom d'un adversaire trop long réduit les dimensions des cases et fait croire un alignement à gauche

[X] Double message Vous avez été attaqués (Rapport) si déjà un message de ce type plus une amélioration ou un recrutement de poulet
	[2/10/2016] Regarde si le message existe déjà avant d'insérer un nouveau message dans Tools::putMessage

[X] Bug des poulets envoyés par Bob qui a 10 masqués et 10 fourcheurs
	[2/10/2016] Prend tous les poulets possibles dans la page arratque-recrutement, met à 0 ce qui ne sont pas dans la table army, et à l'affichage du rapport d'attaque, regarde si l'id du tableau contenant tous les poulets est présent dans celui ne contenant que l'armée de l'attaquant : s'il n'existe pas, alors mets directement le nombre à 0.

[X] Graine ou bois améliorer déjà améliorer (même page) ne peut pas appuyer sur le lien (bouton), idem pour l'accueil
	[8/10/2016] Ajout de variables globales de configuration de jquery mobile :
		$.mobile.changePage.defaults.allowSamePageTransition = true;
		$.mobile.changePage.defaults.reloadPage = true;
	plus modification du script de refresh pour ne pas détruire la page courante si elle est identique
		if ($next_page.context == undefined || $next_page.context.baseURI != $cur_page.context.baseURI) { // Recharger même page

--------------------


--- Idées d'améliorations ---

* Missions spatiales : temps (5 / 10 min) -> BDD
* Missions maritimes : cout en graine ou or : 20 / 50 graines
* Passer la liste des poulets (statiques) de la BDD en statique dans le code, comme pour les missions
* Icône information [i] à côté de l'image des poulets, et quand clic dessus, ouvre une pop-up intégrée (panel) avec un texte de description / humoristique

3/3/2016 à 18:47 :
* Poulet -> mine -> temps d'occupation -> or -> laboratoire -> nouvelle espèce de poulet
* Poulailler -> poule aux oeufs d'or (cher) -> or généré automatiquement -> laboratoire

* Poulet de Bresse, meilleure unité
* Poulets dorés : avec de l'or obtenu en mission ou autre, on peut recruter des poulets dorés aux caractéristiques améliorées
* Poulets avec des armes : avec l'or ou autre, on peut acheter des armes / boucliers / bonus aux poulets
* Laboratoire : la recherche du labo peut avancer avec de l'or, gagné en mission ou bien en envoyant des poulets se faire examiner -> barre de recherche qui avance
* Rendre le jeu accessbile avec une faible connexion internet (edge) sans images ni JS -> remplacer les liens jQuery Mobile

2/10/2016 à 15h30 :
* Poulailler : limite maximum de poulet : agrandir le poulailler pour augmenter le nombre de poulets possible à recruter

--------------------


--- Remarques ---

* All audio and video content belongs to the original author/s and/or copyright holders unless otherwise stated.
	Tout le contenu audio et vidéo appartient à l'auteur / s d'origine et / ou les détenteurs des droits d'auteur, sauf indication contraire.
	Toutes les images appartiennent à leurs auteurs d'origine et/ou les détenteurs des droits d'auteur.

*	Attention : si lors de l'inscription (login -> enregistrement user) le nombre de champs dans la requête d'insertion en base de données des ressources n'est pas équivalent aux nombres d'attributs de la table ressources, alors la création ne se fait pas ! Il faut soit bien penser à mettre à jour les attributs à chaque fois, soit mettre par défaut NULL (ou 0) et autoriser les NULL dans la table.

* jQuery Mobile fichier .css  modification image/ajax-loader.gif vers views/image/ajax-loader.gif
.ui-icon-loading{background:url(images/ajax-loader.gif);
=> .ui-icon-loading{background:url(views/images/ajax-loader.gif);

--------------------


--- Sauvegarde de bouts de code ---

-- 1.

/* Arrête le rafraichissement quand changement de page */
/*$(document).on("pagehide", function() {
	clearInterval(affichageRessources);
});*/

remplacé par (plus haut dans le code)

if (typeof affichageRessources === "undefined") { ... }

---

-- 2.

/* Supprime le cache de la première page */
/*$(document).on("pagecontainershow", function(e, ui) {
	$(ui.prevPage).remove();
});*/

remplacé par

class="refresh-compteur-bois"
+
$('.refresh-compteur-bois').html(parseInt(bois).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + " Bois");

---