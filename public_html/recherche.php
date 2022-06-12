<?php
session_start();
$thisPageTitle = "Randothèque - Recherche"; // Titre de l'onglet
$thisPage = "recherche"; // Pour lier à la bonne feuille CSS

require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo
include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>
	<div id="main">
		<h1>Recherche de trace</h1>
		<?php
		?>
	</div>

</body>

<?php
	include 'php/footer.php';
?>
</html>