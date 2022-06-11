<?php
session_start();
$thisPageTitle = "Randothèque - Chat"; // Titre de l'onglet
$thisPage = "chat"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>

	<p>Chat avec d'autres utilisateurs</p> <!-- miaou -->
</body>

<?php
	include 'php/footer.php';
?>
</html>