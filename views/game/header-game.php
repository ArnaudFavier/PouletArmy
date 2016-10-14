<?php
	require_once('views/header.php');
?>
<div data-role="page">
	<div data-role="header" data-position="fixed">
		<a href="game" id="game-icon-home" class="ui-btn ui-shadow ui-corner-all ui-icon-home ui-btn-icon-notext">Accueil</a>
		<h1><?php echo unserialize($_SESSION['user'])->pseudo . ' (' . unserialize($_SESSION['user'])->point . ' pts)'; ?></h1>
		<div data-role="navbar">
			<ul>
				<li><a href="bois" class="refresh-compteur-bois">
				<?php
				if($_SESSION['ressource']['bois'] >= $_SESSION['ressource']['boisMax']) {
					echo '<span class="texte-rouge">' . Tools::formatNumber($_SESSION['ressource']['bois']) . '</span>';
				} else {
					echo Tools::formatNumber($_SESSION['ressource']['bois']);
				}
				?> Bois</a></li>
				<li><a href="graine" class="refresh-compteur-graine">
				<?php
				if($_SESSION['ressource']['graine'] >= $_SESSION['ressource']['graineMax']) {
					echo '<span class="texte-rouge">' . Tools::formatNumber($_SESSION['ressource']['graine']) . '</span>';
				} else {
					echo Tools::formatNumber($_SESSION['ressource']['graine']);
				}
				?> Graine<?php if($_SESSION['ressource']['graine'] >= 2) { echo 's'; } ?></a></li>
				<li><a href="or"><?php echo $_SESSION['ressource']['or']; ?> Or</a></li>
			</ul>
		</div><!-- div navbar -->
	</div><!-- div header -->
	<div data-role="main" id="home-main" class="ui-content">
		<?php
			if(!empty($_SESSION['message']['success'])) {
				foreach ($_SESSION['message']['success'] as $key => $value) {
					echo '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok-sign"></span> ' . $value . '</div>';
					unset($_SESSION['message']['success'][$key]);
				}
			}
			if(!empty($_SESSION['message']['information'])) {
				foreach ($_SESSION['message']['information'] as $key => $value) {
					echo '<div class="alert alert-info" role="alert">' . $value . '</div>';
					unset($_SESSION['message']['information'][$key]);
				}
			}
			if(!empty($_SESSION['message']['warning'])) {
				foreach ($_SESSION['message']['warning'] as $key => $value) {
					echo '<div class="alert alert-warning" role="alert">' . $value . '</div>';
					unset($_SESSION['message']['warning'][$key]);
				}
			}
			if(!empty($_SESSION['message']['error'])) {
				foreach ($_SESSION['message']['error'] as $key => $value) {
					echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> ' . $value . '</div>';
					unset($_SESSION['message']['error'][$key]);
				}
			}
		?>