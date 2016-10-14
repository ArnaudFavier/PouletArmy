<?php
	require_once('header-game.php');
?>

<div id="game-scierie">
	<h2>Scierie</h2>
	<h6>La scierie permet de produire plus de bois,<br>pour la construction des bâtiments.</h6>
	<img src="views/images/buildings/scierie.png" alt="Scierie">
	<h4>Niveau <strong><?php echo $view['scierieNiveau']; ?></strong></h4>
	<h5>Produit actuellement <strong><?php echo $view['boisHeure']; ?></strong> bois par heure</h5>
	<a href="scierie-ameliorer" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left">Améliorer</a>
	<h5>Prochain niveau : <strong><?php echo $view['scierieBoisAugmentation']; ?></strong> bois/h (+ <?php echo $view['scierieBoisAugmentationDifference']; ?>)</h5>
	<h5>Coût : <strong><?php echo $view['scierieCout']; ?></strong> bois pour améliorer la scierie</h5>
	<hr>
	<h3>Dépôt de bois</h3>
	<h6>Le dépôt de bois permet de stocker plus de bois.</h6>
	<img src="views/images/buildings/stockage.png" alt="Depôt">
	<h5>Niveau <strong><?php echo $view['depotNiveau']; ?></strong></h5>
	<h5>Maximum de <strong><?php echo $view['boisMax']; ?></strong> bois</h5>
	<a href="depot-ameliorer" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left">Améliorer</a>
	<h5>Prochain niveau : <strong><?php echo $view['depotBoisAugmentation']; ?></strong> bois (+ <?php echo $view['depotBoisAugmentationDifference']; ?>)</h5>
	<h5>Coût : <strong><?php echo $view['depotCout']; ?></strong> bois pour améliorer l'entrepôt de bois</h5>
</div>

<?php
	require_once('footer-game.php');
?>