<?php
session_start();
$thisPageTitle = "Randothèque - Importer un fichier GPX"; // Titre de l'onglet
$thisPage = "import"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	include 'php/head.php';
?>

<body>
	<p id="welcome">Importer une trace</p>
</body>

<?php
	include 'php/footer.php';
?>
</html>