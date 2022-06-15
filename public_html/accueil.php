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
	
	// Récupérer les fichier_gpx de l'utilisateur
	$reqFichierGpx = $linkpdo->prepare('SELECT count(Id_Fichier_GPX) FROM fichier_gpx WHERE Id_Utilisateur = :id_util');
	$reqFichierGpx->execute(array('id_util'=>$_SESSION['id_util']));
	
	

?>
<!DOCTYPE html>
<html lang="fr">

	<?php include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
	
	//Bienvenue
	echo "<div class=\"messageBienvenue\">";
		if(file_exists("images/avatars/".$_SESSION['id_util'].".jpg")) {
			echo "<img src=\"images/avatars/".$_SESSION['id_util'].".jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
		} else {
			echo "<img src=\"images/avatars/default.jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
		} ?>
		<p id="welcome">Bienvenue sur Randothèque, <?php echo $_SESSION['nom_util'];?></p>
	</div>
	
	<!--Statistique nb trace-->
		<p class="nbTrace">Vous possédez 
		<?php while($data = $reqFichierGpx->fetch()) {
			echo $data[0];
		}?> traces, détails sur votre <a href="profil.php?id_util=<?php echo $_SESSION['id_util']?>">profil</a></p>

		<!--Meteo-->
		<!-- weather widget start -->
		<div class="meteo" style="width:510px;color:#000;border:1px solid #F2F2F2;padding: 0 10px 10px 10px;">
		<p>Météo</p>
		<iframe height="85" frameborder="0" width="510" scrolling="no" src="http://www.prevision-
		meteo.ch/services/html/toulouse/horizontal ?bg=B6AC96&txtcol=000000&tmpmin=fff000&tmpmax=378ADF "
		allowtransparency="true"></iframe>
		<a style="text-decoration:none;font-size:0.75em;" title="Détail des prévisions pour Toulouse"
		href="http://www.prevision-meteo.ch/meteo/localite/toulouse">Prévisions complètes pour Toulouse</a>
		</div>
		<!-- weather widget end -->
	
</body>
<?php
	include 'php/footer.php';
?>

</html>