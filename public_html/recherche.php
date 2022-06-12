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
	echo "<body>";
	include 'php/head.php';
?>
	<div id="main">
		<h1>Recherche de trace</h1>
		<div id="searchMenu">
			<form id="formsearch" action="recherche.php" method="post">
				<label for="recherche">Recherche :</label>
				<input type="text" name="recherche" id="recherche" placeholder="Saisissez des mots-clef">
				<label for="type_de_sport">Type de sport :</label>
				<select type="text" name="type_de_sport" id="type_de_sport">
					<option value="">Type de sport</option>
					<option value="">Tous les sports</option>
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
					<label for="min">Distance minimum</label>
					<input id="min" class="min" name="min" type="range" step="1" min="0" max="3000" />
					<label for="max">Distance maximum</label>
					<input id="max" class="max" name="max" type="range" step="1" min="0" max="3000" />
				</div>
				<input type="submit" value="Rechercher">
			</form>
		</div>
		<div id="map">
		</div>
		<div id="résultatRechercheTrace">
		</div>
	</div>

</body>

<script>
	/* Credits : https://codepen.io/joosts/pen/rNLdxvK */
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
</script>
<?php
	include 'php/footer.php';
?>
</html>