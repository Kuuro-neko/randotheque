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
if(!empty($_POST['login'] && !empty($_POST['mdp'] && isset($_POST['signin'])))) {
	// Recherche dans la BD
	$req = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
	WHERE (Nom_d_utilisateur LIKE :log_in OR Mail LIKE :log_in) AND Mot_de_passe LIKE :mdp');
	$req->execute(array('log_in'=>$_POST['login'], 'mdp'=>$_POST['mdp']));

	// Si nom util + mdp trouvé ou mail + mdp trouvé -> connecter (SESSION signedin = true)
	if($data = $req->fetch()) {
		$msgErreur = "Bienvenue ".$data['Nom_d_utilisateur']. ", ça marche !";
		$_SESSION['signedin'] = true;
	} else {
	// Sinon msgErreur : mdp ou login erroné
		$msgErreur = "Login ou mot de passe erroné";
	}
}
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