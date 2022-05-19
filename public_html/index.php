<?php
session_start();
$thisPageTitle = "Randothèque - Connexion"; // Titre de l'onglet
$thisPage = "index"; // Pour lier à la bonne feuille CSS

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

<?php
	include 'php/balise_head.php';
?>

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