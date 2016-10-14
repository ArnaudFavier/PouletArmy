<?php
	require_once('header-game.php');
?>

<div id="game-mission">
	<h2>Missions</h2>
	<h6>Les missions permettent d'envoyer des poulets dans diverses aventures en espérant trouver quelque chose à ramener.</h6>
	<h3>- Missions spatiales -</h3>
	<form action="mission-recruter" method="POST">
		<input type="hidden" name="mission" value="1">
		<button class="btn btn-primary game-bouton-<?php echo $view['mission'][1]['couleur']; ?>" type="submit"><?php echo $view['mission'][1]['bouton']; ?></button>
	</form>
	<br>
	<form action="mission-recruter" method="POST">
		<input type="hidden" name="mission" value="2">
		<button class="btn btn-primary game-bouton-<?php echo $view['mission'][2]['couleur']; ?>" type="submit"><?php echo $view['mission'][2]['bouton']; ?></button>
	</form>
	<br>
	<form action="mission-recruter" method="POST">
		<input type="hidden" name="mission" value="3">
		<button class="btn btn-primary game-bouton-<?php echo $view['mission'][3]['couleur']; ?>" type="submit"><?php echo $view['mission'][3]['bouton']; ?></button>
	</form>
	<br>
	<h3>- Missions maritimes -</h3>
	<form action="mission-recruter" method="POST">
		<input type="hidden" name="mission" value="4">
		<button class="btn btn-primary game-bouton-<?php echo $view['mission'][4]['couleur']; ?>" type="submit"><?php echo $view['mission'][4]['bouton']; ?></button>
	</form>
	<br>
</div>

<?php
	require_once('footer-game.php');
?>