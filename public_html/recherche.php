<?php
session_start();
$thisPageTitle = "Randothèque - Recherche"; // Titre de l'onglet
$thisPage = "recherche"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>

	<p>Recherche de trace</p>
</body>

<?php
	include 'php/footer.php';
?>
</html>