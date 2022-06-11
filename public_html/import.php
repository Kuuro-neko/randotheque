<?php
session_start();
$thisPageTitle = "Randothèque - Importer un fichier GPX"; // Titre de l'onglet
$thisPage = "import"; // Pour lier à la bonne feuille CSS

require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo
include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>
	<fieldset id="import">
		<legend class="title">Importer un fichier GPX</legend>
		<form id="formimport" action="import.php" method="post" enctype="multipart/form-data">
			<label for="fichier">Fichier GPX :</label>
			<input type="file" name="gpx_file" id="gpx_file" required>
			<label for="type_de_sport">Type de sport :</label>
			<input type="text" name="type_de_sport" id="type_de_sport" placeholder="Type de sport">
			<label for="localisation">Localisation :</label>
			<input type="text" name="localisation" id="localisation" placeholder="Localisation">
			<label for="difficulte">Difficulté :</label>
			<select name="difficulte" id="difficulte" placeholder="Difficulté">
				<option value="null">Difficulté</option>
				<option value="null">Non renseigné</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			<label for="description">Description :</label>
			<textarea name="description" id="description" placeholder="Description"></textarea>
			<input type="submit" name="import" value="Importer">
		</form>
	</fieldset>
	<!-- YO !!! Ici, le script php qui suit echo soit une balise <p class="error">, soit une balise <p class="success">
		A toi le giga chad qui fais le css, mets la mise en forme pour ces 2 classes, texte en rouge / vert... à toi de voir ! uwu -->
	<?php
		if(isset($_POST["import"])) {
			// Insérer les données du fichier gpx dans la table fichier_gpx de la base de données
			$type_de_sport = $_POST['type_de_sport'];
			$localisation = $_POST['localisation'];
			$description = $_POST['description'];
			if($localisation == "") {
				$localisation = "Non renseigné";
			}
			if($description == "") {
				$description = "Non renseigné";
			}
			if($type_de_sport == "") {
				$type_de_sport = "Non renseigné";
			}
			if(!empty($_POST['difficulte'])) {
				$difficulte = $_POST['difficulte'];
			} else {
				$difficulte = "NULL";
			}
			$id_utilisateur = $_SESSION['id_util'];

			$req=$linkpdo->prepare("INSERT INTO fichier_gpx (Id_Utilisateur, Type_de_sport, Localisation, Difficulte, Description) VALUES (:id_utilisateur, :type_de_sport, :localisation, :difficulte, :description)");
			$req->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_STR);
			$req->bindValue(':type_de_sport', $type_de_sport, PDO::PARAM_STR);
			$req->bindValue(':localisation', $localisation, PDO::PARAM_STR);
			$req->bindValue(':difficulte', $difficulte, PDO::PARAM_STR);
			$req->bindValue(':description', $description, PDO::PARAM_STR);
			
			if($req->execute()) {
				$last_id = $linkpdo->lastInsertId();
				// Changer le Nom du fichier_gpx créé dans la base de donnée
				$req=$linkpdo->prepare("UPDATE fichier_gpx SET Nom = :nom_fichier_gpx WHERE Id_Fichier_Gpx = :id_fichier_gpx");
				$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
				$req->bindValue(':nom_fichier_gpx', $id_utilisateur."_".$last_id, PDO::PARAM_STR);
				$req->execute();
				$target_dir = "gpx/";
				$target_file = $target_dir . basename($_FILES["gpx_file"]["name"]);
				$uploadOk = 1;
				$gpxFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				$file_parts = pathinfo($_FILES["gpx_file"]["name"]);

				switch($file_parts['extension']){
					case "gpx":
						$uploadOk = 1;
						break;
					case "": // Handle file extension for files ending in '.'
					case NULL: // Handle no file extension
					default:
						$uploadOk = 0;
						echo "<p class=\"error\">Le fichier n'est pas un fichier GPX</p>";
						break;
				}
				// Vérification de la taille du fichier
				if ($_FILES["gpx_file"]["size"] > 8000000) {
					echo "<p class=\"error\">Ce fichier est trop volumineux</p>";
					$uploadOk = 0;
				}
				// Vérification s'il y a eu une erreur
				if ($uploadOk == 0) {
					echo "<p class=\"error\">Votre fichier n'a pas été importé</p>";
					// Supprimer le fichier_gpx créé dans la base de donnée
					$req=$linkpdo->prepare("DELETE FROM fichier_gpx WHERE Id_Fichier_Gpx = :id_fichier_gpx");
					$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
					$req->execute();
				// Si tout est ok, upload le fichier
				} else {
					$arr = explode(".", $_FILES["gpx_file"]["name"]);
					$extension = end($arr);
					// TO-DO : Ajouter le fichier GPX à la BD puis renommer le fichier GPX de cette façon : ID utilisateur_ID fichierGPX.gpx
					$target_file = $target_dir.$id_utilisateur."_".$last_id.".".$extension;
					if (move_uploaded_file($_FILES["gpx_file"]["tmp_name"], $target_file)) {
						echo "<p class=\"success\">Le fichier ". basename( $_FILES["gpx_file"]["name"]). " a été importé avec succès.</p>";
					} else {
						echo "<p class=\"error\">Une erreur est survenue lors de l'importation du fichier. Erreur #".$_FILES["gpx_file"]["error"]."</p>";
						// Supprimer le fichier_gpx créé dans la base de donnée
						$req=$linkpdo->prepare("DELETE FROM fichier_gpx WHERE Id_Fichier_Gpx = :id_fichier_gpx");
						$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
						$req->execute();
					}
				}
			} else {
				echo "<p class=\"error\">Une erreur de communication avec la base de données est survenue</p>";
			}
		}
	?>

</body>

<?php
	include 'php/footer.php';
?>
</html>