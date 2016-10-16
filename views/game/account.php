<?php
	require_once('header-game.php');
?>

<div id="game-account">
	<h2>Mon compte</h2>
	<h6>La gestion de vos informations.</h6>
	<h3><?php echo $view['pseudo']; ?></h3>
	<h5>Joue depuis <?php echo $view['nombreJourInscription']; ?> jour<?php if($view['nombreJourInscription']){echo 's';} ?>.</h5>
	<h3>Modifier le mot de passe</h3>
	<form method="POST" action="account">
		<label for="password">Nouveau mot de passe</label>
		<input name="password" id="password" class="form-control" type="password">
		<label for="password-confirmation">Confimer le nouveau mot de passe</label>
		<input name="password-confirmation" id="password-confirmation" class="form-control" type="password">
		<button class="btn btn-primary btn-lg" type="submit">Modifier</button>
	</form>
	<br>
</div>

<?php
	require_once('footer-game.php');
?>