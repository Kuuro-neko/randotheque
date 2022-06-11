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
	echo "<body>";
	include 'php/head.php';
?>
	<form action="import.php" method="post" enctype="multipart/form-data">
		Importer une trace : 
		<input type="file" name="gpx_file" id="gpx_file" required></br>
		<input type="text" name="type_de_sport" id="type_de_sport" placeholder="Type de sport"></br>
		<input type="text" name="localisation" id="localisation" placeholder="Localisation"></br>
		<select name="difficulte" id="difficulte" placeholder="Difficulté">
			<option value="null">Difficulté</option>
			<option value="null">Non renseigné</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select></br>
		<textarea name="description" id="description" placeholder="Description"></textarea></br>
		<input type="submit" name="import" value="Importer">
	</form>
	<?php
		if(isset($_POST["import"])) {
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
					echo "Le fichier n'est pas un fichier GPX";
					break;
			}
			// Vérification de la taille du fichier
			if ($_FILES["gpx_file"]["size"] > 500000) {
				echo "Ce fichier est trop volumineux";
				$uploadOk = 0;
			}
			// Vérification s'il y a eu une erreur
			if ($uploadOk == 0) {
				echo "Votre fichier n'a pas été importé";
			// Si tout est ok, upload le fichier
			} else {
				$extension = end(explode(".", $_FILES["gpx_file"]["name"]));
				// TO-DO : Ajouter le fichier GPX à la BD puis renommer le fichier GPX de cette façon : ID utilisateur_ID fichierGPX.gpx
				// $target_file = $id_util.$id_gpx.".".$extension;
				if (move_uploaded_file($_FILES["gpx_file"]["tmp_name"], $target_file)) {
					echo "Le fichier ". basename( $_FILES["gpx_file"]["name"]). " a été importé avec succès.";
				} else {
					echo "Une erreur est survenue lors de l'importation du fichier";
				}
			}
		}
	?>

</body>

<?php
	include 'php/footer.php';
?>
</html>