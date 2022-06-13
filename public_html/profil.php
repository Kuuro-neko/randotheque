<?php
session_start();
include 'php/deconnexion_utilisateur.php';

$thisPageTitle = "Randothèque - Profil de ".$_SESSION['Nom_util']; // Titre de l'onglet
$thisPage = "profil"; // Pour lier à la bonne feuille CSS

require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>

	<p>Page de profil</p>
</body>

<?php
	include 'php/footer.php';
?>
</html>