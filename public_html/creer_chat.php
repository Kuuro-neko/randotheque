<?php
session_start();
$thisPageTitle = "Randothèque - Créer un groupe de chat"; // Titre de l'onglet
$thisPage = "chat"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
require 'php/config.php';
require 'php/connexiondb.php'; // Connexion à la base de données

include 'php/balise_head.php';
echo "<body>";
include 'php/head.php';
?>

<p>Chat avec d'autres utilisateurs</p> <!-- miaou -->
<div id="formulaire">
<fieldset class="main">
		<legend class="title">Créer un groupe de chat</legend>
		<form id="formcreerchat" method="post" action="creer_chat.php" method="post">
			<label for="nom_chat">Nom du groupe de chat :</label>
			<input type="text" name="nom_chat" id="nom_chat" /></br>
			<label for="liste_util">Liste des utilisateurs :</label>
			<textarea name="liste_util" id="liste_util" rows="10" cols="50"></textarea></br>
			<input type="submit" name="creer_chat" id="creer_chat" value="Créer" />
		</form>
	</fieldset>
</div>

</body>

<?php
include 'php/footer.php';
?>
</html>

