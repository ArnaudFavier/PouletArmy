<?php
	require_once('header-game.php');
?>

<div id="game-champs">
	<h2>Champs</h2>
	<h6>Les champs permettent de produire plus de graines,<br>pour recruter des poulets.</h6>
	<img src="views/images/buildings/champs.gif" alt="Champs">
	<h4>Niveau <strong><?php echo $view['champsNiveau']; ?></strong></h4>
	<h5>Produisent actuellement <strong><?php echo $view['graineHeure']; ?></strong> graines par heure</h5>
	<a href="champs-ameliorer" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left game-bouton-marron">Améliorer</a>
	<h5>Prochain niveau : <strong><?php echo $view['champsGraineAugmentation']; ?></strong> graines/h (+ <?php echo $view['champsGraineAugmentationDifference']; ?>)</h5>
	<h5>Coût : <strong><?php echo $view['champsCout']; ?></strong> bois pour améliorer les champs</h5>
	<hr>
	<h3>Entrepôt de graines</h3>
	<h6>L'entrepôt de graines permet de stocker plus de graines.</h6>
	<img src="views/images/buildings/stockage.png" alt="Entrepôt">
	<h5>Niveau <strong><?php echo $view['entrepotNiveau']; ?></strong></h5>
	<h5>Maximum de <strong><?php echo $view['graineMax']; ?></strong> graines</h5>
	<a href="entrepot-ameliorer" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left game-bouton-marron">Améliorer</a>
	<h5>Prochain niveau : <strong><?php echo $view['entrepotGraineAugmentation']; ?></strong> graines (+ <?php echo $view['entrepotGraineAugmentationDifference']; ?>)</h5>
	<h5>Coût : <strong><?php echo $view['entrepotCout']; ?></strong> bois pour améliorer l'entrepôt de graines</h5>
</div>

<?php
	require_once('footer-game.php');
?>