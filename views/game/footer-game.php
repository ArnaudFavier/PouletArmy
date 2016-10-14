		</div><!-- div main -->
		<div data-role="footer">
			<a href="deconnexion" id="game-icon-deconnexion" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-notext">Déconnexion</a>
			<a href="help" class="ui-btn ui-mini">Aide</a>
			<a href="about" class="ui-btn ui-mini">À propos</a>
		</div><!-- div footer -->

		<!-- JavaScript dans la page pour être rechargé à chaque changement de page -->
		<script type="text/javascript">
			$.mobile.changePage.defaults.reloadPage = true;

			var bois = <?php echo $_SESSION['ressource']['bois']; ?>;
			var boisSeconde = <?php echo $_SESSION['ressource']['boisSeconde']; ?>;
			var boisMax = <?php echo $_SESSION['ressource']['boisMax']; ?>;

			var graine = <?php echo $_SESSION['ressource']['graine']; ?>;
			var graineSeconde = <?php echo $_SESSION['ressource']['graineSeconde']; ?>;
			var graineMax = <?php echo $_SESSION['ressource']['graineMax']; ?>;

			if (typeof affichageRessources === "undefined") { 
				var affichageRessources = setInterval(function(){
					if(bois <= boisMax) {
						bois += boisSeconde;
						if(bois < boisMax) {
							$('.refresh-compteur-bois').html(parseInt(bois).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + " Bois");
						} else {
							$('.refresh-compteur-bois').html("<span class=\"texte-rouge\">" + parseInt(boisMax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "</span> Bois");
						}
					}

					<?php if($_SESSION['ressource']['graineSeconde'] > 0) { ?>
						if(graine <= graineMax) {
							graine += graineSeconde;
							if(graine >= 2) {
								var graineAccord = " Graines";
							} else {
								var graineAccord = " Graine";
							}
							if(graine < graineMax) {
								$('.refresh-compteur-graine').html(parseInt(graine).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + graineAccord);
							} else {
								$('.refresh-compteur-graine').html("<span class=\"texte-rouge\">" + parseInt(graineMax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "</span>" + graineAccord);
							}
						}
					<?php } ?>
				}, 1000);

				/* Supprime page cache */
				$(document).delegate('.ui-page', 'pageinit', function(event) {
					var $cur_page = $(this);
					$sub_pages = $cur_page.siblings('.ui-subpage');
					erasePage = function(event, ui) {
						var $next_page = ui.nextPage;
						if ($next_page) {
							if ($next_page.context == undefined || $next_page.context.baseURI != $cur_page.context.baseURI) { // Recharger même page
								if ($sub_pages.length > 0) {
									$sub_pages.filter(':jqmData(url^="' + $cur_page.jqmData('url') + '&' + $.mobile.subPageUrlKey + '")').remove();
								}
								$cur_page.remove();
							}
						}
					};
					$cur_page.unbind('pagehide.remove');
					$cur_page.bind('pagehide.remove', erasePage);
				});
			}
		</script>

	</div><!-- div page -->

<?php
	require_once('views/footer.php');
?>