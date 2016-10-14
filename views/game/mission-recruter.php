<?php
	require_once('header-game.php');
?>

<div id="game-mission-recruter">
	<h2>Mission</h2>
	<h3 id="game-mission-titre"><?php echo $view['mission']['titre']; ?></h3>
	<h5><?php echo $view['mission']['description']; ?></h5>
	<p>Choisissez le nombre de poulets à envoyer en mission :</p>
	<br>
	<form id="game-mission-recruter-formulaire" action="mission-rapport" method="POST">
		<input type="hidden" name="mission" value="<?php echo $view['mission']['id']; ?>">
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
		<button class="btn btn-primary game-bouton-<?php echo $view['mission']['couleur']; ?>" type="submit">Envoyer</button>
	</form>
</div>

<?php
	require_once('footer-game.php');
?>