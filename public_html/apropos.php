<?php
session_start();
$thisPageTitle = "Randothèque - A propos"; // Titre de l'onglet
$thisPage = "apropos"; // Pour lier à la bonne feuille CSS

?>
<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	echo "<body>";
	if(isset($_SESSION['id_util'])) {
		include 'php/head.php';
	} else {
		include 'php/index_head.php';
		
	}


?>

	<div class="page">
		<div class="titre">
			<h4>A propos</h4>
		</div>
		<div class="texte">
			<div class="case 1">
				<h5>Nous</h5>
				<p>Nous sommes 3 étudiants de l'IUT de Toulouse, nous avons réalisé cette application web dans le cadre d'un projet tutoré.</p>
			</div>
			<div class="case 2">
				<h5>Le client</h5>
				<p>Monsieur BELTRAN Thierry, enseignant chercheur au sein de l’IUT Paul Sabatier de Toulouse, est un grand amateur de sport en tout genre. Comme beaucoup de sportifs, par le biais d’outils GPS
				(Aujourd’hui intégrés dans tous les smartphones et les montres connectées), il génère de nombreuses traces GPS sous format de fichier.gpx. Sont besoins aujourd’hui et de posséder un outils,
				ou plutôt une bibliothèque lui permettant la gestion et le partage de ces nombreuses traces GPS.</p>
			</div>
			<div class="case 3">
				<h5>Le projet</h5>
				<p>Notre site est une bibliothèque de trace GPS pour pratiques sportives diverses (Vélo, course à pied, trail, natation etc…).
				A la manière d’un outil de gestion de document, l'application vous permet l’importation, la gestion, et la recherche de trace GPS, vous pouvez ainsi partager vos parcours sportif préférer, en trouver de nouveau.
				Notre site prévois dans de futur mise à jour la création de groupe de chat afin d'organiser des sortie sportive et de partager vos traces GPS entre amis.</p>
			</div>
		</div>
	</div>

</body>

<?php
	include 'php/footer.php';
?>

	
</html>