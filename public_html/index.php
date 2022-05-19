<?php
session_start();
require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo

$msgErreur = "";

// Rediriger vers l'accueil authentifié si l'utilisateur est déjà connecté
if(!empty($_SESSION['signedin'])) {
	//header -> accueil.php
}

// Si formulaire de connection rempli
	// Si nom util + mdp trouvé ou mail + mdp trouvé -> connecter (SESSION signedin = true)
	// Sinon msgErreur : mdp ou login erroné
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="description" content="Bibliotheque de fichiers GPX">
	<meta name="keywords" content="Projet tutoré, GPX, sport, bibliothèque">
	<meta name="author" content="Clicheroux Shayne & Gonzalez Oropeza Gilles & Cazal Victor & Gouazé Lucie">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Randothèque</title>
	<link rel="stylesheet" href="./css/index.css">
	<link rel="icon" href="./images/logo.png" />
</head>
<body>
    <h1>Randothèque</h1>
    <form action="index.php" class="connexion" method="post">
   		<fieldset>
			<legend class="title">Connexion</legend>
			<input type="text" name="login" placeholder="Nom d'utilisateur" required></input>
			<input type="password" name ="mdp" placeholder="Mot de passe" required></input>
			<?php
			if ($msgErreur != "") {
				echo "<p class=\"error\">".$msgErreur."</p>";
			}
			?>
			<input type="submit" name="signin" value="Se connecter"></input>
		</fieldset>
	</form>
</body>