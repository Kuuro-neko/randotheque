<?php
session_start();
$thisPageTitle = "Randothèque - Visualisation"; // Titre de l'onglet
$thisPage = "visualisation"; // Pour lier à la bonne feuille CSS

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

	// récupérer les données du fichier_gpx dont l'id est passé via $_GET['id_gpx']
	$id_gpx = $_GET['id_gpx'];
	$sql = "SELECT * FROM fichier_gpx WHERE Id_Fichier_GPX = $id_gpx";
	$result = $linkpdo->query($sql);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$id_gpx = $row['Id_Fichier_GPX'];
	$type_de_sport = $row['Type_de_sport'];
	$localisation = $row['Localisation'];
	$difficulte = $row['Difficulte'];
	$description = $row['Description'];
	$distance = $row['Distance'];
	$owner = $row['Id_Utilisateur'];


?>
	
	<div class="formulaire"><fieldset id="import">
		<legend class="title">Informations du fichier</legend>
		<form id="formimport" class="element" action="import.php" method="post" enctype="multipart/form-data">
				<label for="type_de_sport">Type de sport :</label>
				<select type="text" name="type_de_sport" id="type_de_sport">
					<option value="<?php echo $type_de_sport; ?>"><?php echo $type_de_sport; ?></option>
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
				<input type="text" name="localisation" id="localisation" value="<?php echo $localisation; ?>" /> 
			<!--</div>
			<div class="element 4">-->
				<label for="difficulte">Difficulté :</label>
				<select name="difficulte" id="difficulte">
					<option value="<?php echo $difficulte; ?>"><?php echo $difficulte; ?></option>
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
				<textarea name="description" id="description" placeholder="Description" value="<?php echo $description; ?>"></textarea>
			<!--</div>
			<div class="element 6">-->
				<input type="submit" name="import" value="Modifier">
			<!--</div>-->
		</form>
	</fieldset></div>
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
				echo "<p class=\"success\">Le fichier a été modifié avec succès !</p>";
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