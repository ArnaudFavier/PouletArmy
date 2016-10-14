<?php
	require_once('header-game.php');
?>

<div id="game-attaque">
	<h2>Attaques</h2>
	<h6>Les attaques permettent d'envoyer vos poulets combattre contre ceux d'autres joueurs.</h6>
	<br>
	<a href="rapport" class="button-link"><button id="game-attaque-bouton-rapport" class="btn btn-primary game-bouton-jaune">Rapports</button></a>
	<br>
	<form action="attaque-recruter" method="POST">
		<h4>Liste des joueurs :</h4>
		<select name="joueur">
			<?php
				foreach ($view['listeJoueurs'] as $joueur) {
					echo '<option value="' . $joueur['id'] . '">' . $joueur['pseudo'] . '</option>';
				}
			?>
		</select>
		<br>
		<button class="btn btn-primary game-bouton-orange" type="submit">Attaquer</button>
	</form>
</div>

<?php
	require_once('footer-game.php');
?>