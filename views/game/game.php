<?php
	require_once('header-game.php');
?>

<div id="game-main">
	<img src="views/images/buildings/chateau<?php echo $view['chateauNiveau']; ?>.png" alt="Chateau">
	<br>
	<br>
	<div id="game-bouton-action">
		<a id="game-bouton-mission" href="mission" class="button-link"><button class="btn btn-primary game-bouton-bleu">Missions</button></a>
		<a id="game-bouton-attaque" href="attaque" class="button-link"><button class="btn btn-primary game-bouton-orange">Attaques</button></a>
	</div>
	<br>
	<br>
	<br>
	<br>
	<table class="table">
		<tr>
			<td onclick="$.mobile.changePage('bois');" class="game-menu-td-image"><img src="views/images/buildings/scierie.png" class="game-menu-image" alt="Scierie"></td>
			<td onclick="$.mobile.changePage('bois');">Scierie</td>
			<td onclick="$.mobile.changePage('bois');">nv. <?php echo $_SESSION['ressource']['scierie']; ?></td>
			<td onclick="$.mobile.changePage('bois');"><?php echo $view['boisHeure']; ?> /h.</td>
			<td><a href="bois" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
		<tr>
			<td onclick="$.mobile.changePage('graine');" class="game-menu-td-image"><img src="views/images/buildings/champs.gif" class="game-menu-image" alt="Champs"></td>
			<td onclick="$.mobile.changePage('graine');">Champs</td>
			<td onclick="$.mobile.changePage('graine');">nv. <?php echo $_SESSION['ressource']['champs']; ?></td>
			<td onclick="$.mobile.changePage('graine');"><?php echo $view['graineHeure']; ?> /h.</td>
			<td><a href="graine" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
		<tr>
			<td onclick="$.mobile.changePage('or');" class="game-menu-td-image"><img src="views/images/buildings/comptoir.png" class="game-menu-image" alt="Comptoir"></td>
			<td onclick="$.mobile.changePage('or');">Comptoir</td>
			<td colspan="2" onclick="$.mobile.changePage('or');"><?php echo $_SESSION['ressource']['or']; ?> Or</td>
			<td><a href="or" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td onclick="$.mobile.changePage('poulailler');" class="game-menu-td-image"><img src="views/images/buildings/poulailler.png" class="game-menu-image" alt="Poulailler"></td>
			<td onclick="$.mobile.changePage('poulailler');">Poulailler</td>
			<td colspan="2" onclick="$.mobile.changePage('poulailler');"><?php echo $view['nombrePoulets']; ?> poulet<?php if($view['nombrePoulets'] > 1) { echo 's'; } ?></td>
			<td><a href="poulailler" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
		<tr>
			<td onclick="$.mobile.changePage('laboratoire');" class="game-menu-td-image"><img src="views/images/buildings/laboratoire.png" class="game-menu-image" alt="Laboratoire"></td>
			<td onclick="$.mobile.changePage('laboratoire');">Laboratoire</td>
			<td colspan="2" onclick="$.mobile.changePage('laboratoire');">0 recherche</td>
			<td><a href="laboratoire" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4" onclick="$.mobile.changePage('classement');">Classement</td>
			<td><a href="classement" class="ui-btn ui-corner-all ui-icon-carat-r ui-btn-icon-notext bouton-sans-marge">&gt;</a></td>
		</tr>
	</table>
</div>

<?php
	require_once('footer-game.php');
?>