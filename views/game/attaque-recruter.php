<?php
	require_once('header-game.php');
?>

<div id="game-attaque-recruter">
	<h2>Attaque</h2>
	<br>
	<h5>Vous allez attaquer le joueur <strong><?php echo $view['joueur']['pseudo']; ?></strong> possédant <?php echo $view['joueur']['point']; ?> point<?php if($view['joueur']['point'] > 1) { echo 's'; } ?>.</h5>
	<p>Choisissez le nombre de poulets à envoyer au combat :</p>
	<br>
	<form id="game-attaque-recruter-formulaire" action="attaque-rapport" method="POST">
		<input type="hidden" name="joueur" value="<?php echo $view['joueur']['id']; ?>">
		<table class="table">
			<?php foreach ($view['poulets'] as $poulet) {
				echo '<tr><td colspan="3"><h4><span class="game-poulailler-nom">' . $poulet['nom'] . '</span></h4></td></tr>';
				echo '<tr><td><img src="views/images/poulets/' . $poulet['image'] . '" alt="' . $poulet['nom'] . '" class="game-image-poulet" width="75px" heigth="75px"></td>';
				echo '<td><table class="game-poulailler-sous-tableau"><tr><td>Attaque : <span class="game-poulailler-attaque">' . $poulet['attaque'] . '</span></td><td>Défense : <span class="game-poulailler-defense">' . $poulet['defense'] . '</span></td></tr>';
				$pluriel = '';
				if ($poulet['quantite'] > 1) {
					$pluriel = 's';
				}
				echo '<tr><td>Possédé' . $pluriel . ' :</td><td>' . $poulet['quantite'] . '</td></tr>';
				echo '<tr><td>Nombre :</td><td><input name="' . $poulet['id'] . '" class="form-control" type="number" value="" min="0" max="' . $poulet['quantite'] . '" ' . $poulet['disable'] . '></td></tr></table></td></tr>';
			} ?>
		</table>
		<button class="btn btn-primary game-bouton-orange" type="submit">Attaquer</button>
	</form>
</div>

<?php
	require_once('footer-game.php');
?>