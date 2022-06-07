<?php
session_start();
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if(empty($_SESSION['signedin'])) {
	header("Location: index.php");
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
	<p id="welcome">Bienvenue sur le site de gestion du cabinet médical !</p>
</body>
</html>