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
?>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
	<body onload="initialize()">
<?php
	include 'php/head.php';

	// récupérer les données du fichier_gpx dont l'id est passé via $_GET['id_gpx']
	$id_gpx = $_GET['id_gpx'];
	$sql = "SELECT * FROM fichier_gpx, utilisateur WHERE Id_Fichier_GPX = $id_gpx AND fichier_gpx.Id_Utilisateur = utilisateur.Id_Utilisateur";
	$result = $linkpdo->query($sql);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$id_gpx = $row['Id_Fichier_GPX'];
	$type_de_sport = $row['Type_de_sport'];
	$localisation = $row['Localisation'];
	$difficulte = $row['Difficulte'];
	$description = $row['Description'];
	$distance = $row['Distance'];
	$owner = $row['Id_Utilisateur'];
	$owner_name = $row['Nom_d_utilisateur'];

	if($_SESSION['id_util'] == $owner) {
		$disableEdit = "";
		$owner_name = "Vous";
	} else {
		$disableEdit = "disabled=\"disabled\"";
	}
?>
	
	<div class="formulaire"><fieldset id="import">
		<legend class="title">Informations du fichier</legend>
		<form id="formimport" class="element" action="import.php" method="post" enctype="multipart/form-data">
				<label for="localisation">Propriétaire :</label>
				<a href="profil.php?id_util=<?php echo $owner; ?>"><?php echo $owner_name; ?></a> 
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
				<?php
					if($owner_name == "Vous") {
				?>
				<div class="col">
					<input type="submit" name="import" value="Modifier">
				</div>
				<?php
					}
				?>
				
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
	<div id="map"></div>
	<?php
		function parse_waypoints($gpx_file) {
			$xml = simplexml_load_file($gpx_file);
			$waypoints = array();
			foreach ($xml->trk->trkseg as $trkseg) {
				foreach ($trkseg->trkpt as $wpt) {
					$waypoints[] = array(
						'lat' => (float) $wpt->attributes()->lat,
						'lon' => (float) $wpt->attributes()->lon
					);
				}
			}
			return $waypoints;
		}

		// Load gpx/test.gpx, xml file containing the waypoints of a GPX file
		$gpx_file = "gpx/".$owner."_".$id_gpx.".gpx";
		$waypoints = parse_waypoints($gpx_file);
	?>

	<fieldset id="interact_comments">
		<legend class="title">Commentaires</legend>
		<form id="interact" action="visualisation.php?id_gpx=<?php echo $id_gpx; ?>" method="post">
			<div class="element 1">
				<label for="note">Note :</label>
				<input type="range" name="note" id="note" min="0" max="5" value="0" step="0.5" oninput="this.nextElementSibling.value = this.value"/><output>0</output>
			</div>
			<div class="element tarea">
				<label for="commentaire">Commentaire :</label>
				<textarea name="commentaire" id="commentaire" placeholder="Commentaire"></textarea>
			</div>
			<div class="element 3">
				<input type="submit" name="comment" value="Commenter">
			</div>
		</form>
		<?php 
			// Récupérer le contenu du formulaire et insérer les données dans la table interagir avec Id_Utilisateur = $_SESSION['id_util'] et Id_GPX = $id_gpx
			if(isset($_POST["comment"])) {
				$req=$linkpdo->prepare("INSERT INTO interagir (Id_Utilisateur, Id_Fichier_GPX, Note, Commentaire) VALUES (:id_utilisateur, :id_gpx, :note, :commentaire)");
				$req->execute(array(
					':id_utilisateur' => $_SESSION['id_util'],
					':id_gpx' => $_GET['id_gpx'],
					':note' => $_POST['note'],
					':commentaire' => $_POST['commentaire']
				));
			}
		?>

		<div id="separator"></div>


		<div id="comments">	
		<?php
			$req=$linkpdo->prepare("SELECT * FROM fichier_gpx, interagir, utilisateur WHERE fichier_gpx.Id_Fichier_GPX = :id_gpx AND fichier_gpx.Id_Fichier_GPX = interagir.Id_Fichier_GPX AND interagir.Id_Utilisateur = utilisateur.Id_Utilisateur");
			$req->bindValue(':id_gpx', $id_gpx, PDO::PARAM_INT);
			$req->execute();
			$interaction = $req->fetchAll();
			foreach($interaction as $inter) {
				?>
				<div class="comment">
					<div class="comment_header">
						<div class="comment_header_left">
							<?php 
								if(file_exists("images/avatars/".$inter['Id_Utilisateur'].".jpg")) {
									echo "<img src=\"images/avatars/".$inter['Id_Utilisateur'].".jpg\" alt=\"Avatar de ".$inter['Nom_d_utilisateur']."\" height=\"60\">";
								} else {
									echo "<img src=\"images/avatars/default.jpg\" alt=\"Avatar de ".$inter['Nom_d_utilisateur']."\" height=\"100\">";
								}
							 ?>
						</div>
						<div class="comment_header_right">
							<p class="comment_author"><?php echo $inter['Nom_d_utilisateur']; ?></p>
							<p class="comment_note">Note : <?php echo $inter['Note']; ?> / 5</p>
						</div>
					</div>
					<div class="comment_content">
						<p class="comment_text"><?php echo $inter['Commentaire']; ?></p>
					</div>
				</div>
				<?php
			}
		?>
		</div>
	</fieldset>



<?php
	include 'php/footer.php';
?>
</body>


<script type="text/javascript">
    function initialize() {
        var map = L.map('map', {gestureHandling: true}).setView([<?php echo $waypoints[(count($waypoints)/2)]['lat']; ?>, <?php echo $waypoints[(count($waypoints)/2)]['lon']; ?>], 13.5); // LIGNE 18

        var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { // LIGNE 20
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        });

		// Create a polyline from $waypoints
		var polyline = L.polyline(<?php echo json_encode($waypoints); ?>, {
			color: 'red',
			weight: 5,
			opacity: 1,
			smoothFactor: 1
		});

		// Add the polyline to the map
		polyline.addTo(map);


        map.addLayer(osmLayer);
    }
</script>
</html>