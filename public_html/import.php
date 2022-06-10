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
	include 'php/head.php';
?>

<body>
	<p id="welcome">Importer une trace</p>
	<form action="" method="post">
	<label for="gpx">Importer une trace : </label>

	<input type="file"
		id="gpx" name="gpx"
		accept="xml/gpx" required>
	<input type="submit" value="Importer">
	</form>

	<?php
	// Store the gpx file in gpx/ folder
	if (isset($_FILES['gpx'])) {
		$file = $_FILES['gpx'];
		$fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
		$fileSize = $file['size'];
		$fileError = $file['error'];
		$fileType = $file['type'];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		$allowed = array('gpx');
		
	
	}


	?>
</body>

<?php


	include 'php/footer.php';
?>
</html>