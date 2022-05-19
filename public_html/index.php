<?php
session_start();
require 'php/config.php';
$msgErreur = "";
// Rediriger vers l'accueil authentifié si l'utilisateur est déjà connecté
if(!empty($_SESSION['signedin'])) {
	header("Location: accueil.php");
}
// Si formulaire de connection rempli
if(!empty($_POST['login']) && !empty($_POST['mdp']) && empty($_SESSION['signedin'])) {
    //Essayer de se connecter avec les valeurs
   if($_POST['login'] === $loginSite && $_POST['mdp'] === $mdpSite) {
	   $_SESSION['signedin'] = true;
	   header("Location: accueil.php");
   } else {
	   $msgErreur = "Login ou mdp erroné";
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
			<input type="submit" name="signin" value="Se connecter"></input>
		</fieldset>
	</form>
</body>