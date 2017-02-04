<?php
	require_once('header-game.php');
?>

<div id="game-poulailler">
	<h2>Poulailler</h2>
	<h6>Le poulailler permet de recruter des poulets dans l'armée.</h6>
	<img src="views/images/buildings/poulailler.png" alt="Poulailler">
	<br>
	<?php
		// Si le poulailler n'est pas construit, affichage du bouton de construction, sinon affichage de la liste des poulets
		if ($view['poulailler']['niveau'] == 0) {
			echo '<br>';
			echo '<a href="poulailler-construire" class="ui-btn ui-corner-all ui-btn-inline ui-icon-arrow-u-r ui-btn-icon-left game-bouton-marron">Construire</a><br>';
			echo 'Coût : <strong>' . $view['poulailler']['cout'] . '</strong> bois';
		} else {
			echo '<br><p>Choisissez le nombre de poulets à recruter :</p>';
			echo '<br>';
			echo '<form id="game-poulailler-formulaire" action="poulet-recruter" method="POST">';
				echo '<table class="table">';
					foreach ($view['poulets'] as $poulet) {
						echo '<tr><td colspan="3"><h4><span class="game-poulailler-nom">' . $poulet['nom'] . '</span></h4></td></tr>';
						echo '<tr><td><img src="views/images/poulets/' . $poulet['image'] . '" alt="' . $poulet['nom'] . '" class="game-image-poulet" width="75px" heigth="130px"></td>';
						echo '<td><table class="game-poulailler-sous-tableau"><tr><td>Attaque : <span class="game-poulailler-attaque">' . $poulet['attaque'] . '</span></td><td>Défense : <span class="game-poulailler-defense">' . $poulet['defense'] . '</span></td></tr>';
						echo '<tr><td>Coût :</td><td><span class="game-poulailler-prix">' . $poulet['cout'] . '</span> graines</td></tr>';
						$pluriel = '';
						if ($poulet['quantite'] > 1) {
							$pluriel = 's';
						}
						echo '<tr><td>Possédé' . $pluriel . ' :</td><td>' . $poulet['quantite'] . '</td></tr>';
						echo '<tr><td>Maximum :</td><td><span class="game-poulailler-maximum">' . $poulet['maximum'] . '</td></span></tr>';
						echo '<tr><td colspan="1">Nombre :</td><td><input name="' . $poulet['id'] . '" class="form-control" type="number" value="" min="0" max="' . $poulet['maximum'] . '" ' . $poulet['disable'] . '></td></tr></table></td></tr>';
					}
				echo '</table>';
				echo '<button class="btn btn-primary" type="submit">Recruter</button>';
			echo '</form>';
		}
	?>
</div>

<?php
	require_once('footer-game.php');
?>