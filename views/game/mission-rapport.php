<?php
	require_once('header-game.php');
?>

<div id="game-mission-rapport">
	<h2>Rapport de mission</h2>
	<h3 id="game-mission-titre"><?php echo $view['mission']['titre']; ?></h3>
	<h5><?php echo $view['mission']['description']; ?></h5>
	<hr>
	<h4>Le <?php echo $view['rapport']['date']; ?> à <?php echo $view['rapport']['heure']; ?></h4>
	<p><strong>Départ : <?php echo $view['rapport']['departPoulets']; ?> poulet<?php if($view['rapport']['departPoulets'] > 1) { echo 's'; } ?></strong></p>
	<p><strong>Retour : <?php echo $view['rapport']['arriveePoulets']; ?> poulet<?php if($view['rapport']['arriveePoulets'] > 1) { echo 's'; } ?></strong></p>
	<p><?php echo $view['rapport']['message']; ?></p>
</div>

<?php
	require_once('footer-game.php');
?>