<?php
session_start();
$thisPageTitle = "Randothèque - Connexion"; // Titre de l'onglet
$thisPage = "index"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	include 'php/head.php';
?>

<body>
	<p id="welcome">Bienvenue sur Randothèque, <?php echo $_SESSION['nom_util'];?></p>
</body>

<?php
	include 'php/footer.php';
?>
</html>