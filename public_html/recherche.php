<?php
session_start();
$thisPageTitle = "Randothèque - Recherche"; // Titre de l'onglet
$thisPage = "recherche"; // Pour lier à la bonne feuille CSS

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
?>
	<div id="main">
		<h1>Recherche de trace</h1>
		<div id="searchMenu">
			<form id="formsearch" action="recherche.php" method="post">
				<label for="recherche">Recherche :</label>
				<?php
					// Si des mots-clef ont été saisis, on les affiche
					if(isset($_POST['recherche'])){
						$value = "value=\"".$_POST['recherche']."\"";
						$placeholder = "";
					} else {
						$value = "";
						$placeholder = "Saisissez des mots-clef";
					}
				?>
				<input type="text" name="recherche" id="recherche" placeholder="<?php echo $placeholder; ?>" <?php echo $value; ?> />
				<label for="type_de_sport">Type de sport :</label>
				<select type="text" name="type_de_sport" id="type_de_sport">
					<?php 
						// Si le type de sport a été rempli, on le réaffiche
						if(isset($_POST['type_de_sport'])){
							if($_POST['type_de_sport'] != "%"){
								echo "<option value='".$_POST['type_de_sport']."'>".$_POST['type_de_sport']."</option>";
							}
						}
					?>
					<option value="%">Type de sport</option>
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
				</select>
				<label for="distance" id="distance">Distance :</label>
				<div class="min-max-distance" data-legendnum="2">
					<?php
						// Si le min et le max ont été remplis, on les affiche
						if(isset($_POST['min']) && isset($_POST['max'])){
							$min_distance = $_POST['min'];
							$max_distance = $_POST['max'];
						} else {
							$min_distance = 0;
							$max_distance = 3000;
						}
					?>
					<label for="min">Distance minimum</label>
					<input id="min" class="min" name="min" type="range" step="1" min="0" max="3000" value="<?php echo $min_distance; ?>">
					<label for="max">Distance maximum</label>
					<input id="max" class="max" name="max" type="range" step="1" min="0" max="3000" value="<?php echo $max_distance; ?>">
				</div>
				<input type="submit" value="Rechercher">
			</form>
		</div>
		<?php
			// si le formulaire est rempli
			if(isset($_POST['recherche'])){
						// Récupération des données de la recherche
				$recherche = $_POST['recherche'];
				$type_de_sport = $_POST['type_de_sport'];
				$min = $_POST['min'];
				$max = $_POST['max'];

				// Requete en fonction des données de la recherche dans la table fichier_gpx de la base de données
				$sql = "SELECT * FROM fichier_gpx, utilisateur WHERE fichier_gpx.Id_Utilisateur = utilisateur.Id_Utilisateur AND (Description LIKE '%$recherche%' OR Localisation LIKE '%$recherche%') AND Type_de_sport LIKE '%$type_de_sport%' AND Distance BETWEEN $min AND $max";
				if($result = $linkpdo->query($sql)) {
					$result->setFetchMode(PDO::FETCH_ASSOC);
					$resultat = $result->fetchAll();
				}

			}
		?>
		<div id="résultatRechercheTrace">
			<?php
				// Afficher les résultats de la recherche
				if(isset($resultat)){

					echo "<table class=\"gpx_table\">";
					echo "<tr><th>Description</th><th>Type de sport</th><th>Difficulté</th><th>Localisation</th><th>Distance</th><th>Propriétaire</th><th>Visualiser la trace</th></tr>";
					foreach($resultat as $gpx) {
						// Afficher la Description, le Type_de_sport, la Difficulté et la Localisation du $gpx dans les lignes du tableau
						echo "<tr><td>".$gpx['Description']."</td><td>".$gpx['Type_de_sport']."</td><td>".$gpx['Difficulte']."</td><td>".$gpx['Localisation']."</td><td>".round($gpx['Distance'], 2)." km</td><td><a href=\"profil.php?id_util=".$gpx['Id_Utilisateur']."\">".$gpx['Nom_d_utilisateur']."</a><td><a href=\"visualisation.php?id_gpx=".$gpx['Id_Fichier_GPX']."\">Visualiser</a></td></tr>";
					}
					echo "</table>";

				}
			?>
		</div>
	</div>
<?php
	include 'php/footer.php';
?>
</body>
<script type="text/javascript">
	/* Double range slider credits : https://codepen.io/joosts/pen/rNLdxvK */
	var thumbsize = 14;

	function draw(slider,splitvalue) {

		/* set function vars */
		var min = slider.querySelector('.min');
		var max = slider.querySelector('.max');
		var lower = slider.querySelector('.lower');
		var upper = slider.querySelector('.upper');
		var legend = slider.querySelector('.legend');
		var thumbsize = parseInt(slider.getAttribute('data-thumbsize'));
		var rangewidth = parseInt(slider.getAttribute('data-rangewidth'));
		var rangemin = parseInt(slider.getAttribute('data-rangemin'));
		var rangemax = parseInt(slider.getAttribute('data-rangemax'));

		/* set min and max attributes */
		min.setAttribute('max',splitvalue);
		max.setAttribute('min',splitvalue);

		/* set css */
		min.style.width = parseInt(thumbsize + ((splitvalue - rangemin)/(rangemax - rangemin))*(rangewidth - (2*thumbsize)))+'px';
		max.style.width = parseInt(thumbsize + ((rangemax - splitvalue)/(rangemax - rangemin))*(rangewidth - (2*thumbsize)))+'px';
		min.style.left = '0px';
		max.style.left = parseInt(min.style.width)+'px';
		min.style.top = lower.offsetHeight+'px';
		max.style.top = lower.offsetHeight+'px';
		legend.style.marginTop = min.offsetHeight+'px';
		slider.style.height = (lower.offsetHeight + min.offsetHeight + legend.offsetHeight)+'px';
		
		/* correct for 1 off at the end */
		if(max.value>(rangemax - 1)) max.setAttribute('data-value',rangemax);

		/* write value and labels */
		max.value = max.getAttribute('data-value'); 
		min.value = min.getAttribute('data-value');
		lower.innerHTML = min.getAttribute('data-value');
		upper.innerHTML = max.getAttribute('data-value');

	}

	function init(slider) {
		/* set function vars */
		var min = slider.querySelector('.min');
		var max = slider.querySelector('.max');
		var rangemin = parseInt(min.getAttribute('min'));
		var rangemax = parseInt(max.getAttribute('max'));
		var avgvalue = (rangemin + rangemax)/2;
		var legendnum = slider.getAttribute('data-legendnum');

		/* set data-values */
		min.setAttribute('data-value',rangemin);
		max.setAttribute('data-value',rangemax);
		
		/* set data vars */
		slider.setAttribute('data-rangemin',rangemin); 
		slider.setAttribute('data-rangemax',rangemax); 
		slider.setAttribute('data-thumbsize',thumbsize); 
		slider.setAttribute('data-rangewidth',slider.offsetWidth);

		/* write labels */
		var lower = document.createElement('span');
		var upper = document.createElement('span');
		lower.classList.add('lower','value');
		upper.classList.add('upper','value');
		lower.appendChild(document.createTextNode(rangemin));
		upper.appendChild(document.createTextNode(rangemax));
		slider.insertBefore(lower,min.previousElementSibling);
		slider.insertBefore(upper,min.previousElementSibling);
		
		/* write legend */
		var legend = document.createElement('div');
		legend.classList.add('legend');
		var legendvalues = [];
		for (var i = 0; i < legendnum; i++) {
			legendvalues[i] = document.createElement('div');
			var val = Math.round(rangemin+(i/(legendnum-1))*(rangemax - rangemin));
			legendvalues[i].appendChild(document.createTextNode(val));
			legend.appendChild(legendvalues[i]);

		} 
		slider.appendChild(legend);

		/* draw */
		draw(slider,avgvalue);

		/* events */
		min.addEventListener("input", function() {update(min);});
		max.addEventListener("input", function() {update(max);});
	}

	function update(el){
		/* set function vars */
		var slider = el.parentElement;
		var min = slider.querySelector('#min');
		var max = slider.querySelector('#max');
		var minvalue = Math.floor(min.value);
		var maxvalue = Math.floor(max.value);
		
		/* set inactive values before draw */
		min.setAttribute('data-value',minvalue);
		max.setAttribute('data-value',maxvalue);

		var avgvalue = (minvalue + maxvalue)/2;

		/* draw */
		draw(slider,avgvalue);
	}

	var sliders = document.querySelectorAll('.min-max-distance');
	sliders.forEach( function(slider) {
		init(slider);
	});
	/* End double range slider JS code */
</script>
</html>