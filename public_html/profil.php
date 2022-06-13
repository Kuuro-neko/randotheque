<?php
session_start();
include 'php/deconnexion_utilisateur.php';
require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo

// Récupérer le Nom_d_utilisateur lié à l'id_util en $_GET
$reqUsername = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
WHERE Id_Utilisateur = :id_util');
$reqUsername->execute(array('id_util'=>$_GET['id_util']));
$data = $reqUsername->fetch();
$userName = $data['Nom_d_utilisateur'];

$thisPageTitle = "Randothèque - Profil de ".$userName; // Titre de l'onglet
$thisPage = "profil"; // Pour lier à la bonne feuille CSS

?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>
	<fieldset id="mainProfile">
		<legend id="profileTitle">Profil de <?php echo $userName; ?></legend>
		<?php
			if($_SESSION['id_util'] == $_GET['id_util']) {
				echo "Mon profil";
			} else {
				echo "Profil de machin";
			}
		?>
	</fieldset>
</body>

<?php
	include 'php/footer.php';
?>
</html>