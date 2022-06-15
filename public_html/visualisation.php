<?php
session_start();
$thisPageTitle = "Randothèque - Visualisation"; // Titre de l'onglet
$thisPage = "visualisation"; // Pour lier à la bonne feuille CSS

require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo
require 'php/functions.php';
include 'php/deconnexion_utilisateur.php';


if(isset($_POST['download'])) {
	$file = "gpx/".$_SESSION['id_util']."_".$_GET['id_gpx'].".gpx";
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=$file");
	header("Content-Type: application/zip");
	header("Content-Transfer-Encoding: binary");

	// read the file from disk
	readfile($file);
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
?>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
    <script src="//unpkg.com/leaflet-gesture-handling"></script>
	<body onload="initialize()">
<?php
	include 'php/head.php';

	// récupérer les données du fichier_gpx dont l'id est passé via $_GET['id_gpx']
	$id_gpx = $_GET['id_gpx'];
	$sql = "SELECT * FROM fichier_gpx, utilisateur WHERE Id_Fichier_GPX = :id_gpx AND fichier_gpx.Id_Utilisateur = utilisateur.Id_Utilisateur";
	$req = $linkpdo->prepare($sql);
	$req->execute(array('id_gpx'=>$id_gpx));
	if($row = $req->fetch()) { // Si le fichier_gpx existe, alors on affiche la page, sinon message d'erreur juste avant le footer
	$type_de_sport = $row['Type_de_sport'];
	$localisation = $row['Localisation'];
	$difficulte = $row['Difficulte'];
	$description = $row['Description'];
	$distance = $row['Distance'];
	$owner = $row['Id_Utilisateur'];
	$owner_name = $row['Nom_d_utilisateur'];

	$sql = "SELECT AVG(Note) AS Note_moyenne FROM interagir WHERE Id_Fichier_GPX = :id_gpx";
	$result = $linkpdo->prepare($sql);
	$result->execute(array(':id_gpx' => $id_gpx));
	if($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$note_moyenne = $row['Note_moyenne'];
	}
	if($note_moyenne == null) {
		$note_moyenne = "Aucune note";
	} else {
		$note_moyenne = $note_moyenne . "/5";
	}

	if($_SESSION['id_util'] == $owner) {
		$disableEdit = "";
		$owner_name = "Vous";
	} else {
		$disableEdit = "disabled=\"disabled\"";
	}
?>
	<?php
		if(isset($_POST["edit"])) {
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

			$req=$linkpdo->prepare("UPDATE fichier_gpx set Id_Utilisateur=:id_utilisateur, Type_de_sport=:type_de_sport, Localisation=:localisation, Difficulte=:difficulte, Description=:description where Id_Fichier_GPX=:Id_Fichier_GPX");
            if($req->execute(array(
                'id_utilisateur' => $id_utilisateur,
                'type_de_sport' => $type_de_sport,
                'localisation' => $localisation,
                'difficulte' => $difficulte,
                'description' => $description,
                'Id_Fichier_GPX' => $id_gpx
			))) {
				echo "<p class=\"success\">Le fichier a été modifié avec succès !</p>";
			} else {
				echo "<div class=\"message\"><p class=\"error\">Une erreur de communication avec la base de données est survenue<br/>";
				var_dump($linkpdo->errorInfo()); echo "</p></div>";
			}
		}
	?>
	<div class="formulaire">
		<fieldset id="edit">
		<legend class="title">Informations du fichier</legend>
		<form id="formedit" class="element" action="visualisation.php?id_gpx=<?php echo $id_gpx; ?>" method="post">
			<div class="propriétaire">
				<label for="localisation">Propriétaire :</label>
					<a href="profil.php?id_util=<?php echo $owner; ?>"><?php echo $owner_name; ?></a> 
					</div>
				<div class="note_moyenne">
					<label for="note_moyenne">Note moyenne :&nbsp;</label>
					<?php echo $note_moyenne; ?>
				</div>
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
				<label for="localisation">Localisation :</label>
				<input type="text" name="localisation" id="localisation" value="<?php echo $localisation; ?>" /> 
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
				<label for="description">Description :</label>
				<textarea name="description" id="description" 
				<?php 
					if($description == "Non renseigné" || $description == "") {
						echo "placeholder=\"Description\">";
					} else {
						?>><?php
						echo $description;
					}
				?>
				</textarea>
				<?php
					if($owner_name == "Vous") {
				?>
				<div class="col">
					<input type="submit" name="edit" value="Modifier">
				</div>
				<?php
					}
				?>
		</form>
	</fieldset>
	<fieldset id="share"><legend class=title>Partager</legend>
		<form id="formshare" class="element" action="visualisation.php?id_gpx=<?php echo $_GET['id_gpx']; ?>" method="post">
		<?php
			$sql = "SELECT * FROM participer, conversation WHERE participer.Id_Utilisateur = :util AND participer.Id_Conversation = conversation.Id_Conversation";
			$req = $linkpdo->prepare($sql);
			$req->execute(array('util' => $_SESSION['id_util']));
			echo "<div class=\"col\">";
			echo "<label for=\"conversation\">Conversation :</label>";
			if($result = $req->fetchAll()) {
				echo "<select name=\"conversation\" id=\"conversation\">";
				foreach($result as $row) {
					$id_conversation = $row['Id_Conversation'];
					$nom_conversation = $row['Libelle'];
					echo "<option value=\"$id_conversation"."||"."$nom_conversation"."\">$nom_conversation<br>";
				}
				echo "</select>";
				echo "</div>";
				echo "<input type=\"submit\" name=\"share\" value=\"Partager dans un groupe de chat\">";
			} else {
				echo "<label class=\"error conversation\"> &nbsp;Vous n'etes présents dans aucun groupe de chat.</label>";
				echo "</div>";
			}
		?>
		<div class="separator"></div>
		<?php

			// Bouton pour télécharger la trace gpxw
			echo "<input type=\"submit\" name=\"download\" value=\"Télécharger la trace\">";
			// Si le bouton de téléchargement est cliqué, on télécharge le fichier gpx
			
		

			// Si la trace a été partagée, on l'envoie dans la conversation choisie
			if (isset($_POST['share'])) {
				$message = "[trace=".$_GET['id_gpx']."]";
				$paramconv = explode("||", $_POST['conversation']);
				$id_conv = $paramconv[0];
				$nom_conv = $paramconv[1];

				$req = envoyerMessage($_SESSION['id_util'], $id_conv, $message);

				if ($req) {
					echo "<p class=\"success\">Trace partagée dans le groupe de chat : <a href=\"chat.php?id_conv=".$id_conv."&conv_name=".$nom_conv."\"> ".$nom_conv."</a>.</p>";
				} else {
					echo "<p class=\"error\">Erreur lors du partage de la trace.</p>";
				}
			}
		?>
		</form>
	</fieldset>
</div>
	
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

		<?php
			// Vérifier si le fichier_gpx a déjà un commentaire pour l'utilisateur connecté et assigner les vlaeurs à des variables
			$req=$linkpdo->prepare("SELECT * FROM interagir WHERE Id_Utilisateur = :id_util AND Id_Fichier_GPX = :id_gpx");
			$req->execute(array('id_util' => $_SESSION['id_util'],'id_gpx' => $id_gpx));
			if ($donnees = $req->fetch()) {
				if($donnees['Commentaire'] != "") {
					$comment = $donnees['Commentaire'];
					$alreadyCommented = true;
				} else {
					$comment = "";
					$alreadyCommented = false;
				}
				$note = $donnees['Note'];
			} else {
				$alreadyCommented = false;
				$note = 2.5;
				$comment = "";
			}

			// Si le bouton de commentaire est cliqué, on enregistre le commentaire dans la base de données
			if(!$alreadyCommented) {
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
			} else {
				// Récupérer le contenu du formulaire et update les données dans la table interagir avec Id_Utilisateur = $_SESSION['id_util'] et Id_GPX = $id_gpx
				if(isset($_POST["comment"])) {
					$req=$linkpdo->prepare("UPDATE interagir SET Note = :note, Commentaire = :commentaire WHERE Id_Utilisateur = :id_utilisateur AND Id_Fichier_GPX = :id_gpx");
					$req->execute(array(
						':id_utilisateur' => $_SESSION['id_util'],
						':id_gpx' => $_GET['id_gpx'],
						':note' => $_POST['note'],
						':commentaire' => $_POST['commentaire']
					));
					$comment = $_POST['commentaire'];
				}	
			}
		?>

	<fieldset id="interact_comments">
		<legend class="title">Commentaires</legend>
		<form id="interact" action="visualisation.php?id_gpx=<?php echo $id_gpx; ?>" method="post">
			<div class="element 1">
				<label for="note">Note :</label>
				<input type="range" name="note" id="note" min="0" max="5" value="<?php echo $note; ?>" step="0.5" oninput="this.nextElementSibling.value = this.value"/><output><?php echo $note; ?></output>
			</div>
			<div class="element tarea">
				<label for="commentaire">Votre commentaire :</label>
				<textarea name="commentaire" id="commentaire" 
				<?php 
					if($alreadyCommented) {
						?>><?php
						echo $comment;
					} else {
						echo "placeholder=\"Commentaire\">";
					}
				?>
			</textarea>
			</div>
			<div class="element 3">
				<input type="submit" name="comment" value="<?php echo ($alreadyCommented) ? "Modifier" : "Commenter"; ?>">
			</div>
		</form>


		<div id="separator"></div>

		<div id="comments">	
		<?php
			$req=$linkpdo->prepare("SELECT * FROM fichier_gpx, interagir, utilisateur WHERE fichier_gpx.Id_Fichier_GPX = :id_gpx AND fichier_gpx.Id_Fichier_GPX = interagir.Id_Fichier_GPX AND interagir.Id_Utilisateur = utilisateur.Id_Utilisateur");
			$req->execute(array('id_gpx' => $id_gpx));
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
							<a href="profil.php?id_util=<?php echo $inter['Id_Utilisateur'] ?>"><p class="comment_author"><?php echo $inter['Nom_d_utilisateur']; ?></p></a>
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
	} else {
		echo "<div class=\"message\"><p class=\"error\">Fichier GPX Inexistant</p></div>";
	}
	include 'php/footer.php';
?>
</body>

<script type="text/javascript">
    function initialize() {
        var map = L.map('map', {fullscreenControl: true, gestureHandling: true}).setView([<?php echo $waypoints[(count($waypoints)/2)]['lat']; ?>, <?php echo $waypoints[(count($waypoints)/2)]['lon']; ?>], 13.5); // LIGNE 18

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