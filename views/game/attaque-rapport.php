<?php
	require_once('header-game.php');
?>

<div id="game-attaque-rapport">
	<h2>Rapport de combat</h2>
	<p>Attaque contre <strong><?php echo $view['joueur']['pseudo']; ?></strong></p>
	<br>
	<p>Le <?php echo $view['rapport']['date']; ?> à <?php echo $view['rapport']['heure']; ?></p>
	<?php if (!empty($view['rapport']['meteo'])) { ?>
		<h5>Météo lors de l'attaque :</h5>
		<img src="views/images/weather/<?php echo $view['rapport']['meteo']; ?>.png" alt="Météo" width="100" height="100">
		<h6>La météo influe sur l'efficacité des poulets de l'attaquant.</h6>
	<?php } ?>
	<table id="game-attaque-table" class="table">
		<tr class="game-table-header">
			<th>Attaquant</th>
			<th>Défenseur</th>
		</tr>
		<tr>
			<td><i><?php echo unserialize($_SESSION['user'])->pseudo; ?></i></td>
			<td><i><?php echo $view['joueur']['pseudo']; ?></i></td>
		</tr>
		<tr>
			<td><?php echo unserialize($_SESSION['user'])->point; ?> point<?php if (unserialize($_SESSION['user'])->point > 1) { echo 's'; } ?></td>
			<td><?php echo $view['joueur']['point']; ?> point<?php if ($view['joueur']['point'] > 1) { echo 's'; } ?></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Début du combat</strong></td>
		</tr>
		<tr>
			<td><strong><?php echo $view['rapport']['departPoulets']; ?> poulet<?php if ($view['rapport']['departPoulets'] > 1) { echo 's'; } ?></strong></td>
			<td><strong><?php echo $view['nombrePouletsDefenseurDebut']; ?> poulet<?php if ($view['nombrePouletsDefenseurDebut'] > 1) { echo 's'; } ?></strong></td>
		</tr>
		<?php
			foreach ($view['listePoulets'] as $poulet) {
				/* Attaquant */
				$idPoulet = array_search($poulet['id'], array_column($view['pouletsAttaquant'], 'id'));
				if ($idPoulet === false) {
					$nombreAttaquant = 0;
				} else {
					$nombreAttaquant = $view['pouletsAttaquant'][$idPoulet]['nombre'];
				}

				$plurielAttaquant = '';
				if ($nombreAttaquant > 1) {
					$plurielAttaquant = 's';
				}

				/* Defenseur */
				$plurielDefenseur = '';
				$nombreDefenseur = $view['pouletsDefenseurDebut'][array_search($poulet['id'], array_column($view['pouletsDefenseurDebut'], 'id'))]['quantite'];
				if ($nombreDefenseur > 1) {
					$plurielDefenseur = 's';
				} else {
					// Pas de défense pour le défenseur
					$nombreDefenseur = 0;
				}

				echo '<tr>';
				echo '<td colspan="2">' . $poulet['nom'] .' :</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>' . $nombreAttaquant . ' poulet' . $plurielAttaquant . '</td>';
				echo '<td>' . $nombreDefenseur . ' poulet' . $plurielDefenseur . '</td>';
				echo '</tr>';
			}
		?>
		<tr>
			<td colspan="2"><strong>Fin du combat</strong></td>
		</tr>
		<tr>
			<td><strong><?php echo $view['rapport']['arriveePoulets']; ?> poulet<?php if ($view['rapport']['arriveePoulets'] > 1) { echo 's'; } ?></strong></td>
			<td><strong><?php echo round($view['nombrePouletsDefenseurFin']); ?> poulet<?php if ($view['nombrePouletsDefenseurFin'] > 1) { echo 's'; } ?></strong></td>
		</tr>
		<?php
			foreach ($view['listePoulets'] as $poulet) {
				/* Attaquant */
				$plurielAttaquant = '';
				$nombreAttaquant = $view['rapport']['pouletsAttaquant'][array_search($poulet['id'], array_column($view['rapport']['pouletsAttaquant'], 'id'))]['nombre'];
				if ($nombreAttaquant > 1) {
					$plurielAttaquant = 's';
				}

				/* Defenseur */
				$plurielDefenseur = '';
				$nombreDefenseur = $view['pouletsDefenseurFin'][array_search($poulet['id'], array_column($view['pouletsDefenseurFin'], 'id'))]['quantite'];
				if ($nombreDefenseur > 1) {
					$plurielDefenseur = 's';
				} else {
					// Pas de défense pour le défenseur
					$nombreDefenseur = 0;
				}

				echo '<tr>';
				echo '<td colspan="2">' . $poulet['nom'] .' :</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>' . round($nombreAttaquant) . ' poulet' . $plurielAttaquant . '</td>';
				echo '<td>' . round($nombreDefenseur) . ' poulet' . $plurielDefenseur . '</td>';
				echo '</tr>';
			}
		?>
	</table>
	<br>
	<p><?php echo $view['rapport']['message']; ?></p>
	<br>
	<h4><strong>Vainqueur :</strong></h4>
	<h3><?php echo $view['rapport']['vainqueur']; ?></h3>
</div>

<?php
	require_once('footer-game.php');
?>