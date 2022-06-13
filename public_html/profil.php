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
		<form method="post" action="profil.php?id_util="<?php echo $_GET['id_util']; ?>>
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
					<input type="submit" value="Modifier le profil" name="submit">
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