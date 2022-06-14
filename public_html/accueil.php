<?php
session_start();
$thisPageTitle = "Randothèque - Accueil"; // Titre de l'onglet
$thisPage = "accueil"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo

	$reqUsername = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
	WHERE Id_Utilisateur = :id_util');
	$reqUsername->execute(array('id_util'=>$_SESSION['id_util']));
	if($data = $reqUsername->fetch()) {
		$userName = $data['Nom_d_utilisateur'];
	} else {
		/*header("Location: profil.php?id_util=".$_SESSION['id_util']."&err=null_profile");*/
	}
	
	// Si une image a été uploadée
	if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
		// Récupérer l'extension de l'image
		$extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
		// Si l'image est un jpg
		if($extension == "jpg" || $extension == "jpeg") {
			// Récupérer l'image
			$image = $_FILES['avatar']['tmp_name'];
			// Créer un nom unique pour l'image
			$nomImage = $_SESSION['id_util'].$extension;
			// Déplacer l'image dans le dossier images
			move_uploaded_file($image, "images/avatars/".$nomImage);
		} else {
			// Sinon, afficher un message d'erreur
			echo "<script>alert('Votre image doit être un jpg');</script>";
		}
	}

?>
<!DOCTYPE html>
<html lang="fr">

	<?php include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
	
	echo "<div class=\"messageBienvenue\">";
		if(file_exists("images/avatars/".$_SESSION['id_util'].".jpg")) {
			echo "<img src=\"images/avatars/".$_SESSION['id_util'].".jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
		} else {
			echo "<img src=\"images/avatars/default.jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
		} ?>
		<p id="welcome">Bienvenue sur Randothèque, <?php echo $_SESSION['nom_util'];?></p>
	</div>
	
</body>

<?php
	include 'php/footer.php';
?>
</html>