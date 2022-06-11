<?php
session_start();
$thisPageTitle = "Randothèque - A propos"; // Titre de l'onglet
$thisPage = "apropos"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>

	<p>A propos</p>
</body>

<?php
	include 'php/footer.php';
?>
</html>