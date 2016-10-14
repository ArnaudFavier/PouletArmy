<?php
	require_once('header-game.php');
?>

<div id="game-classement">
	<h2>Classement</h2>
	<h6>Le classement général des 100 meilleurs joueurs de <strong>Poulet Army</strong>.</h6>
	<br>
	<table class="table">
		<tr class="game-table-header"><th>Position</th><th>Pseudo</th><th>Points</th></tr>
		<?php for($i = 0; $i < count($view['classement']); $i++) {
			echo '<tr id="game-classement-position-' . ($i + 1) . '"><td>' . ($i + 1) . '</td><td class="game-classement-pseudo">' . $view['classement'][$i]['pseudo'] . '</td><td>' . $view['classement'][$i]['point'] . '</td></tr>';
		} ?>
	</table>
</div>

<?php
	require_once('footer-game.php');
?>