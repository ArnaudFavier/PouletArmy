<?php
	require_once('header-game.php');
?>

<div id="game-rapport">
	<h2>Rapports</h2>
	<h6>La liste des rapports concernant les <?php echo $view['nombreRapportMax']; ?> dernières attaques reçues.</h6>
	<br>
	<?php
		if (!empty($view['listeRapports'])) {
			foreach ($view['listeRapports'] as $rapport) {
				echo 'Le ' . $rapport['dateAttaque'] . ' à ' . $rapport['heureAttaque'] . ' :<br>';
				echo 'Attaque de <strong>' . $rapport['pseudoAttaquant'] . '</strong> avec ' . $rapport['nbPouletAttaquant'] . ' poulet' . ($rapport['nbPouletAttaquant'] > 1 ? 's' : '') . '.<br>' ;
				echo 'Vous avez perdu ' . $rapport['nbPouletDefenseurPerdu'] . ' poulet' . ($rapport['nbPouletDefenseurPerdu'] > 1 ? 's' : '') . '.<br>' ;
				if ($rapport['bois'] > 0 || $rapport['graine'] > 0) {
					echo 'Vos ressources ont été pillées :<br>';
					echo '<strong>' . $rapport['bois'] . '</strong> bois et <strong>' . $rapport['graine'] . '</strong> graine' . ($rapport['graine'] > 1 ? 's' : '') . '.<br>';
				} else {
					echo 'Aucune de vos ressources n\'a été pillée.<br>';
				}
				echo '<hr>';
			}
		} else {
			echo 'Aucune attaque reçue pour le moment.<br>';
		}
	?>
	<br>
	<a href="javascript:history.go(-1)" class="ui-btn ui-shadow ui-corner-all ui-icon-back ui-btn-icon-notext centrer">Retour</a>
</div>

<?php
	require_once('footer-game.php');
?>