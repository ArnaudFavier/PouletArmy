<?php
	require_once('header-home.php');
?>

<div id="home-titre">
	<h1 id="poulet">Poulet</h1>
	<h1 id="army">Army</h1>
</div>

<?php
	if (isset($cookieDisabled) && $cookieDisabled == true) {
		echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> Cookies désactivés.<br>Le jeu nécéssite l\'activation des cookies.</div>';
	}
	if (!empty($_SESSION['message']['error'])) {
		foreach ($_SESSION['message']['error'] as $key => $value) {
			echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> ' . $value . '</div>';
			unset($_SESSION['message']['error'][$key]);
		}
	}
	if (!empty($_SESSION['message']['information'])) {
		foreach ($_SESSION['message']['information'] as $key => $value) {
			echo '<div class="alert alert-info" role="alert">' . $value . '</div>';
			unset($_SESSION['message']['information'][$key]);
		}
	}
?>

<div id="home-formulaire">
	<form action="login" method="POST">
		<label for="pseudo">Pseudo</label>
		<input name="pseudo" id="pseudo" class="form-control" type="text">
		<br>
		<label for="password">Mot de passe</label>
		<input name="password" id="password" class="form-control" type="password">
		<br>
		<button class="btn btn-primary btn-lg" type="submit">Jouer</button>
		<br>
	</form>
</div>

<div id="home-description" class="panel panel-default">
	<div class="panel-body">
		<p><strong>Poulet Army</strong> est un jeu stratégique de combats de poulets.</p>
		<p>Pour jouer, entrez un <i>pseudo</i> et un <i>mot de passe</i>, puis cliquez sur le bouton <strong>Jouer</strong> !</p>
	</div>
</div>
<?php
	require_once('footer-home.php');
?>