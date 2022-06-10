<?php
session_start();
$thisPageTitle = "Randothèque - Connexion"; // Titre de l'onglet
$thisPage = "index"; // Pour lier à la bonne feuille CSS

require 'php/config.php';
require 'php/connexiondb.php'; // Crée $linkpdo

$msgErreurConnect = "";
$msgErreurInscription = "";

// Rediriger vers l'accueil authentifié si l'utilisateur est déjà connecté
if(!empty($_SESSION['signedin'])) {
	header("Location: acceuil.php");
}

// Si formulaire de connection rempli (les champs sont forcément remplis grâce au required du <form>)
if(isset($_POST['connection'])) {
	// Recherche dans la BD
	$reqConnect = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
	WHERE (Nom_d_utilisateur LIKE :log_in OR Mail LIKE :log_in) AND Mot_de_passe LIKE :mdp_connect');
	$reqConnect->execute(array('log_in'=>$_POST['login_connect'], 'mdp_connect'=>$_POST['mdp_connect']));

	// Si nom util + mdp trouvé ou mail + mdp trouvé -> connecter (SESSION signedin = true)
	if($data = $reqConnect->fetch()) {
		$_SESSION['signedin'] = true;
		$_SESSION['nom_util'] = $data['Nom_d_utilisateur'];
		header("Location: accueil.php");
	} else {
	// Sinon msgErreurConnection : mdp ou login erroné
		$msgErreurConnect = "Login ou mot de passe erroné";
	}
}

// Si formulaire d'inscription rempli (les champs sont forcément remplis grâce au required du <form>)
if(isset($_POST['inscription'])) {
	// Recherche dans la BD si le nom d'utilisateur ou le mail sont déjà utilisés. Si oui => message erreur, si non => inscription (ajouter l'utilisateur à la BD)
	// Nom d'utilisateur
	$reqUsername = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
	WHERE Nom_d_utilisateur LIKE :log_in');
	$reqUsername->execute(array('log_in'=>$_POST['login_inscription']));

	// Mail
	$reqMail = $linkpdo->prepare('SELECT Nom_d_utilisateur FROM utilisateur
	WHERE Mail LIKE :log_in');
	$reqMail->execute(array('log_in'=>$_POST['login_inscription']));

	// Vérifier si le nom d'utilisateur ou le mail sont déjà utilisés
	if($data = $reqUsername->fetch()) {
		$msgErreurInscription = "Ce nom d'utilisateur est déjà utilisé";
	} else if($data = $reqMail->fetch()) {
		$msgErreurInscription = "Ce mail est déjà utilisé";
	} else {
		// Ajouter l'utilisateur à la BD
		$reqInscription = $linkpdo->prepare('INSERT INTO utilisateur (Nom_d_utilisateur, Mail, Mot_de_passe)
		VALUES (:log_in, :mail, :mdp_inscription)');
		$reqInscription->execute(array('log_in'=>$_POST['login_inscription'], 'mail'=>$_POST['mail_inscription'], 'mdp_inscription'=>$_POST['mdp_inscription']));
		$msgErreurInscription = "Inscription réussie, vous pouvez vous connecter";
	}
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php
	include 'php/balise_head.php';
	include 'php/head.php';
?>

<body>
    <form id="connection" action="index.php" class="connexion" method="post">
   		<fieldset>
			<legend class="title">Connexion</legend>
			<input type="text" name="login_connect" placeholder="Nom d'utilisateur" required></input>
			<input type="password" name ="mdp_connect" placeholder="Mot de passe" required></input>
			<?php
			if ($msgErreurConnect != "") {
				echo "<p class=\"error\">".$msgErreurConnect."</p>";
			}
			?>
			<input type="submit" name="connection" value="Se connecter"></input>
		</fieldset>
	</form>

	<form id="inscription" action="index.php" class="connexion" method="post">
   		<fieldset>
			<legend class="title">Inscription</legend>
			<input type="text" name="login_inscription" placeholder="Nom d'utilisateur" required></input>
			<input type="email" name="mail_inscription" placeholder="Adresse mail" required></input>
			<input type="password" name ="mdp_inscription" placeholder="Mot de passe" required></input>
			<?php
			if ($msgErreurInscription != "") {
				echo "<p class=\"error\">".$msgErreurInscription."</p>";
			}
			?>
			<input type="submit" name="inscription" value="S'inscrire"></input>
		</fieldset>
	</form>


<?php
	include 'php/footer.php';
?>
</body>