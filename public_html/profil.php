<?php
session_start();
include 'php/deconnexion_utilisateur.php';
require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo

// Récupérer les données de l'utilisateur dont on va afficher le profil
$reqUsername = $linkpdo->prepare('SELECT * FROM utilisateur
WHERE Id_Utilisateur = :id_util');
$reqUsername->execute(array('id_util'=>$_GET['id_util']));
$data = $reqUsername->fetch();
$userName = $data['Nom_d_utilisateur'];
$mail = $data['Mail'];
$poids = $data['Poids'];
$taille = $data['Taille'];
$dateN = $data['DateN'];
$sexe = $data['Sexe'];

if($_SESSION['id_util'] != $_GET['id_util']) {
	$disableEdit = "disabled=\"disabled\"";
} else {
	$disableEdit = "";
}

$thisPageTitle = "Randothèque - Profil de ".$userName; // Titre de l'onglet
$thisPage = "profil"; // Pour lier à la bonne feuille CSS

// Si le formulaire de modification de profil est rempli
if(isset($_POST['modifier'])) {
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

	// Si le champ de modification de nom est rempli
	if(!empty($_POST['nom'])) {
		// Si le nom est différent du nom actuel
		if($_POST['nom'] != $userName) {
			// Modifier le nom dans la base de données
			$reqNom = $linkpdo->prepare('UPDATE utilisateur
			SET Nom_d_utilisateur = :nom
			WHERE Id_Utilisateur = :id_util');
			$reqNom->execute(array('nom'=>$_POST['nom'], 'id_util'=>$_SESSION['id_util']));
			$userName = $_POST['nom'];
		}
	}
	// Si le champ de modification de mail est rempli
	if(!empty($_POST['mail'])) {
		// Si le mail est différent du mail actuel
		if($_POST['mail'] != $mail) {
			// Modifier le mail dans la base de données
			$reqMail = $linkpdo->prepare('UPDATE utilisateur
			SET Mail = :mail
			WHERE Id_Utilisateur = :id_util');
			$reqMail->execute(array('mail'=>$_POST['mail'], 'id_util'=>$_SESSION['id_util']));
			$mail = $_POST['mail'];
		}
	}
	// Si le champ de modification de poids est rempli
	if(!empty($_POST['poids'])) {
		// Si le poids est différent du poids actuel
		if($_POST['poids'] != $poids) {
			// Modifier le poids dans la base de données
			$reqPoids = $linkpdo->prepare('UPDATE utilisateur
			SET Poids = :poids
			WHERE Id_Utilisateur = :id_util');
			$reqPoids->execute(array('poids'=>$_POST['poids'], 'id_util'=>$_SESSION['id_util']));
			$poids = $_POST['poids'];
		}
	}
	// Si le champ de modification de taille est rempli
	if(!empty($_POST['taille'])) {
		// Si la taille est différente de la taille actuelle
		if($_POST['taille'] != $taille) {
			// Modifier la taille dans la base de données
			$reqTaille = $linkpdo->prepare('UPDATE utilisateur
			SET Taille = :taille
			WHERE Id_Utilisateur = :id_util');
			$reqTaille->execute(array('taille'=>$_POST['taille'], 'id_util'=>$_SESSION['id_util']));
			$taille = $_POST['taille'];
		}
	}
	// Si le champ de modification de date de naissance est rempli
	if(!empty($_POST['dateN'])) {
		// Si la date de naissance est différente de la date de naissance actuelle
		if($_POST['dateN'] != $dateN) {
			// Modifier la date de naissance dans la base de données
			$reqDateN = $linkpdo->prepare('UPDATE utilisateur
			SET DateN = :dateN
			WHERE Id_Utilisateur = :id_util');
			$reqDateN->execute(array('dateN'=>$_POST['dateN'], 'id_util'=>$_SESSION['id_util']));
			$dateN = $_POST['dateN'];
		}
	}
}


?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>
	<fieldset class="main">
		<legend class="title">Profil de <?php echo $userName; ?></legend>
		<form method="post" action="profil.php?id_util=<?php echo $_SESSION['id_util']; ?> ">
			<fieldset id="contenuProfil" <?php echo $disableEdit; ?>>
				<div class="col">
				<?php 
					if(file_exists("images/avatars/".$_GET['id_util'].".jpg")) {
						echo "<img src=\"images/avatars/".$_GET['id_util'].".jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
					} else {
						echo "<img src=\"images/avatars/default.jpg\" alt=\"Avatar de ".$userName."\" height=\"100\">";
					}

					if($_SESSION['id_util'] == $_GET['id_util']) {
				?>
					<label for="avatar">Changer d'avatar :</label>
					<input type="file" name="avatar" id="avatar">
				<?php
					}
				?>
				</div>
				<div class="col">
					<label for="username">Nom d'utilisateur :</label>
					<input type="text" id="username" name="username" value="<?php echo $userName; ?>"/>
				</div>
				<?php
				if($_SESSION['id_util'] == $_GET['id_util']) {
				?>
				<div class="col">
					<label for="mail">Mail :</label>
					<input type="text" id="mail" name="mail" value="<?php echo $mail; ?>"/>
				</div>
				<?php
					}
				?>
				<div class="col">
					<label for="poids">Poids :</label>
					<input type="text" id="poids" name="poids" value="<?php echo $poids; ?>"/>
				</div>
				<div class="col">
					<label for="taille">Taille :</label>
					<input type="text" id="taille" name="taille" value="<?php echo $taille; ?>"/>
				</div>
				<div class="col">
					<label for="dateN">Date de naissance :</label>
					<input type="text" id="dateN" name="dateN" value="<?php echo $dateN; ?>"/>
				</div>
				<div class="col">
					<label for="Sexe">Sexe :</label>
					<input type="text" id="Sexe" name="Sexe" value="<?php
						if($sexe == 'F') {
							echo "Femme";
						} else if ($sexe == 'M') {
							echo "Homme";
						} else {
							echo "Non renseigné / Autre";
					}?>"/>
				</div>
				<?php
				if($_SESSION['id_util'] == $_GET['id_util']) {
				?>
				<div class="col">
					<input type="submit" value="Modifier le profil" name="modifier">
				</div>
				<?php
					}
				?>
			</fieldset>
		</form>
	</fieldset>

	<fieldset class="main">
		<legend class="title">Traces GPX de <?php echo $userName; ?></legend>
		<div id="tracks">
			<img src="images/notfound.png" alt="Aucune trace trouvée" height="100">
			<p id="notfound">Aucune trace importée pour l'instant</p>
		</div>
	</fieldset>
</body>

<?php
	include 'php/footer.php';
?>
</html>