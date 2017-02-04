<?php
	require_once('header-game.php');
?>

<div id="game-or">
	<h2>Comptoir</h2>
	<h6>Le comptoir permet d'échanger du bois contre de l'or.</h6>
	<img src="views/images/buildings/comptoir.png" alt="Comptoir">
	<br>
	<?php
		if ($view['comptoir']['niveau'] == 0) {
			echo '<br>';
			echo '<a href="comptoir-construire" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left game-bouton-marron">Construire</a><br>';
			echo 'Coût : <strong>' . $view['comptoir']['coutConstruction'] . '</strong> bois';
		} else {
			echo '<br><p>Voulez-vous échanger<br>';
			echo '<strong>' . $view['comptoir']['coutEchange'] . '</strong> bois contre <strong>' . $view['comptoir']['gainOr'] . '</strong> or ?</p>';
			echo '<a href="comptoir-echanger" class="ui-btn ui-corner-all ui-btn-inline ui-icon-recycle ui-btn-icon-left game-bouton-jaune">Echanger</a><br>';
		}
	?>
</div>

<?php
	require_once('footer-game.php');
?>