<?php
	require_once('header-game.php');
?>

<div id="game-classement">
	<h2>Classement</h2>
	<h6>Le classement général des 100 meilleurs joueurs de <strong>Poulet Army</strong>.</h6>
	<form>
		<div id="flip-classement" data-role="fieldcontain">
			<label for="flip-classement-checkbox"></label>
			<select id="flip-classement-checkbox" name="flip-classement-checkbox" data-role="flipswitch">
				<option selected="">Points</option>
				<option>Or</option>
			</select>
			<br><span id="game-classement-flip-arrow-right">→</span><span id="game-classement-flip-arrow-left" style="display: none;">←</span>
		</div>
	</form>
	<br>
	<table id="game-classement-points" class="table">
		<tr class="game-table-header"><th>Position</th><th>Pseudo</th><th>Points</th></tr>
		<?php for($i = 0; $i < count($view['classementPoints']); $i++) {
			echo '<tr id="game-classement-position-' . ($i + 1) . '"><td>' . ($i + 1) . '</td><td class="game-classement-pseudo">' . $view['classementPoints'][$i]['pseudo'] . '</td><td>' . $view['classementPoints'][$i]['point'] . '</td></tr>';
		} ?>
	</table>
	<table id="game-classement-or" class="table" style="display: none;">
		<tr class="game-table-header"><th>Position</th><th>Pseudo</th><th>Or</th></tr>
		<?php for($i = 0; $i < count($view['classementOr']); $i++) {
			echo '<tr id="game-classement-position-' . ($i + 1) . '"><td>' . ($i + 1) . '</td><td class="game-classement-pseudo">' . $view['classementOr'][$i]['pseudo'] . '</td><td>' . $view['classementOr'][$i]['or'] . '</td></tr>';
		} ?>
	</table>
</div>
<script>
	$('#flip-classement-checkbox').on('change', function() {
		if($(this)[0].selectedIndex == 1) {
			$('#game-classement-points').hide();
			$('#game-classement-flip-arrow-right').hide();
			$('#game-classement-or').show();
			$('#game-classement-flip-arrow-left').show();
		} else {
			$('#game-classement-or').hide();
			$('#game-classement-flip-arrow-left').hide();
			$('#game-classement-points').show();
			$('#game-classement-flip-arrow-right').show();
		}
	});
</script>

<?php
	require_once('footer-game.php');
?>