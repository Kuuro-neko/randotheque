<?php
session_start();
$thisPageTitle = "Randothèque - A propos"; // Titre de l'onglet
$thisPage = "apropos"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	include 'php/head.php';
?>

	<div class="page">
		<div class="titre">
			<h4>A propos</h4>
		</div>
		<div class="texte">
			<div class="case 1">
				<h5>Nous</h5>
				<p>Nous sommes trois étudiants en DUT informatique année spéciale à l'IUT de Paul Sabatier - Toulouse :<br/>
				Gilles Gonzales Oropeza<br/>
				Shayne Clicheroux
				Victor Cazal<br/>
				Nous adressons une petite pensée à Lucie Gouzé qui nous a quitté en cours de projet.</p>
			</div>
			<div class="case 2">
				<h5>Le client</h5>
				<p>Monsieur BELTRAN Thierry, enseignant chercheur au sein de l’IUT Paul Sabatier de Toulouse, est un grand amateur de sport en tout genre. Comme beaucoup de sportifs, par le biais d’outils GPS (Aujourd’hui intégrés dans tous les smartphones et les montres connectées), il génère de nombreuses traces GPS sous format de fichier.gpx. Sont besoins aujourd’hui et de posséder un outils, ou plutôt une bibliothèque lui permettant la gestion et le partage de ces nombreuses traces GPS.</p>
			</div>
			<div class="case 3">
				<h5>Le projet</h5>
				<p>Notre projet consiste à la réalisation d'une bibliothèque de trace GPS pour pratiques sportives. Elle se veut simple d'utilisation, éfficace et interactive.</p>
			</div>
		</div>
	</div>

</body>

<?php
	include 'php/footer.php';
?>

	
</html>