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
	
	<div class="formulaire"><fieldset id="import">
		<legend class="title">Importer un fichier GPX</legend>
		<form id="formimport" class="element" action="import.php" method="post" enctype="multipart/form-data">
			<!--<div class="element 1">-->
				<label for="fichier">Fichier GPX :</label>
				<input type="file" name="gpx_file" id="gpx_file" required>
			<!--</div>
			<div class="element 2">-->
				<label for="type_de_sport">Type de sport :</label>
				<select type="text" name="type_de_sport" id="type_de_sport">
					<option value="">Type de sport</option>
					<option value="">Non renseigné</option>
					<option value="Vélo">Vélo</option>
					<option value="Course à pied">Course à pied</option>
					<option value="Natation">Natation</option>
					<option value="Randonnée">Randonnée</option>
					<option value="Marche">Marche</option>
					<option value="Ski alpin">Ski alpin</option>
					<option value="Ski de randonnée">Ski de randonnée</option>
					<option value="Canoë">Canoë</option>
					<option value="Roller">Roller</option>
					<option value="Kayak">Kayak</option>
					<option value="Kitesurf">Kitesurf</option>
					<option value="Ski nordique">Ski nordique</option>
					<option value="Escalade">Escalade</option>
					<option value="Ski à roulettes">Ski à roulettes</option>
					<option value="Aviron">Aviron</option>
					<option value="Snowboard">Snowboard</option>
					<option value="Raquettes">Raquettes</option>
					<option value="Stand up paddle">Stand up paddle</option>
					<option value="Surf">Surf</option>
					<option value="Fauteuil roulant">Fauteuil roulant</option>
					<option value="Other">Autre</option>
				</select>
			<!--</div>
			<div class="element 3">-->
				<label for="localisation">Localisation :</label>
				<input type="text" name="localisation" id="localisation" placeholder="Localisation">
			<!--</div>
			<div class="element 4">-->
				<label for="difficulte">Difficulté :</label>
				<select name="difficulte" id="difficulte">
					<option value="null">Difficulté</option>
					<option value="null">Non renseigné</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
			<!--</div>
			<div class="element 5">-->
				<label for="description">Description :</label>
				<textarea name="description" id="description" placeholder="Description"></textarea>
			<!--</div>
			<div class="element 6">-->
				<input type="submit" name="import" value="Importer">
			<!--</div>-->
		</form>
	</fieldset></div>
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
			$req->bindValue(':difficulte', $difficulte, PDO::PARAM_INT);
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
						echo "<div class=\"message\"><p class=\"error\">Le fichier n'est pas un fichier GPX</p></div>";
						break;
				}
				// Vérification de la taille du fichier
				if ($_FILES["gpx_file"]["size"] > 8000000) {
					echo "<div class=\"message\"><p class=\"error\">Ce fichier est trop volumineux</p></div>";
					$uploadOk = 0;
				}
				// Vérification s'il y a eu une erreur
				if ($uploadOk == 0) {
					echo "<div class=\"message\"><p class=\"error\">Votre fichier n'a pas été importé</p></div>";
					// Supprimer le fichier_gpx créé dans la base de donnée
					$req=$linkpdo->prepare("DELETE FROM fichier_gpx WHERE Id_Fichier_Gpx = :id_fichier_gpx");
					$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
					$req->execute();
				// Si tout est ok, upload le fichier
				} else {
					$arr = explode(".", $_FILES["gpx_file"]["name"]);
					$extension = end($arr);
					$target_file = $target_dir.$id_utilisateur."_".$last_id.".".$extension;
					if (move_uploaded_file($_FILES["gpx_file"]["tmp_name"], $target_file)) {
						echo "<div class=\"message\"><p class=\"success\">Le fichier ". basename( $_FILES["gpx_file"]["name"]). " a été importé avec succès.</p></div>";

						// Donner le corps de la fonction distance
						function haversineGreatCircleDistance(
							$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
						  {
							// convert from degrees to radians
							$latFrom = deg2rad($latitudeFrom);
							$lonFrom = deg2rad($longitudeFrom);
							$latTo = deg2rad($latitudeTo);
							$lonTo = deg2rad($longitudeTo);
						  
							$latDelta = $latTo - $latFrom;
							$lonDelta = $lonTo - $lonFrom;
						  
							$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
							  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
							return $angle * $earthRadius;
						  }

						// Calculer la distance entre tous les waypoints du fichier gpx
						$distance = 0;
						$xml = simplexml_load_file($target_file);
						$last_lat = false;
						$last_lon = false;
						$total_distance = 0;
						foreach($xml->trk->trkseg as $trkseg) {
							foreach($trkseg->trkpt as $wpt) {
								$trkptlat = (float) $wpt->attributes()->lat;
								$trkptlon = (float) $wpt->attributes()->lon;
								if($last_lat){
									$total_distance+=haversineGreatCircleDistance($trkptlat, $trkptlon, $last_lat, $last_lon);
								}
								$last_lat = $trkptlat;
								$last_lon = $trkptlon;
							}
						}
						// Ajouter la distance calculée dans la base de données
						$req=$linkpdo->prepare("UPDATE fichier_gpx SET Distance = :distance WHERE Id_Fichier_Gpx = :id_fichier_gpx");
						$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
						$req->bindValue(':distance', $total_distance, PDO::PARAM_STR);
						$req->execute();

					} else {
						echo "<div class=\"message\"><p class=\"error\">Une erreur est survenue lors de l'importation du fichier. Erreur #".$_FILES["gpx_file"]["error"]."</p></div>";
						// Supprimer le fichier_gpx créé dans la base de donnée
						$req=$linkpdo->prepare("DELETE FROM fichier_gpx WHERE Id_Fichier_Gpx = :id_fichier_gpx");
						$req->bindValue(':id_fichier_gpx', $last_id, PDO::PARAM_STR);
						$req->execute();
					}
				}
			} else {
				echo "<div class=\"message\"><p class=\"error\">Une erreur de communication avec la base de données est survenue<br/>";
				var_dump($linkpdo->errorInfo()); echo "</p></div>";
			}
		}
	?>

</body>

<?php
	include 'php/footer.php';
?>
</html>