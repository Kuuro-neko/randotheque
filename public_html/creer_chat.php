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

<div id="formulaire">
<fieldset class="main">
		<legend class="title">Créer un groupe de chat</legend>
		<form id="formcreerchat" method="post" action="creer_chat.php">
			<label for="nom_chat">Nom du groupe de chat :</label>
			<input type="text" name="nom_chat" id="nom_chat" placeholder="Randonnée entre potes ce week-end"/>
			<label for="liste_util">Liste des utilisateurs à ajouter au groupe :</label>
			<textarea name="liste_util" id="liste_util" placeholder="Saisissez des noms des autres utilisateurs à ajouter séparés d'une virgule" style="resize: none;" required></textarea>
			<input type="submit" name="creer_chat" id="creer_chat" value="Créer" />
		</form>
		<?php
			// Si le formulaire est rempli
			if(isset($_POST['creer_chat'])) {
				// On récupère les données du formulaire
				$nom_chat = "Chat de ".$_SESSION['nom_util'];
				if(isset($_POST['nom_chat'])) {
					if($_POST['nom_chat'] != "") {
						$nom_chat = $_POST['nom_chat'];
					}
				}
				$liste_util = $_POST['liste_util'];
				// Vérification que les utilisateurs saisis existent bien
				$liste_util = explode(",", $liste_util);
				$liste_util = array_map('trim', $liste_util);
				$liste_util = array_map('strtolower', $liste_util);
				$liste_util = array_map('ucfirst', $liste_util);
				$sql = "SELECT Id_Utilisateur FROM utilisateur WHERE Nom_d_utilisateur = :util";
				foreach($liste_util as $util) {
					if($util != "" && $util != " ") {
						$req = $linkpdo->prepare($sql);
						$req->execute(array('util' => $util));
						$utilisateur_inconnu = false;
						if($resultat = $req->fetch()) {
							$liste_util[$util] = $resultat['Id_Utilisateur'];
						} else {
							$utilisateur_inconnu = true;
							break;
						}
					}
				}
				if(!$utilisateur_inconnu) {
					// On crée le chat
					$sql = "INSERT INTO conversation (Libelle) VALUES (:libelle)";
					$req = $linkpdo->prepare($sql);
					$req->execute(array('libelle' => $nom_chat));
					$id_conv = $linkpdo->lastInsertId();
					
					// On ajoute les utilisateurs au chat
					$sql = "INSERT INTO participer (Id_Utilisateur, Id_Conversation) VALUES (:id_util, :id_conv)";
					$req = $linkpdo->prepare($sql);
					// D'abord l'utilisateur connecté
					$req->execute(array(
						'id_util' => $_SESSION['id_util'],
						'id_conv' => $id_conv
					));
					// Puis ceux saisis
					foreach($liste_util as $util) {
						$req->execute(array(
							'id_util' => $util,
							'id_conv' => $id_conv
						));
					}
					echo "<p class='success'>Le chat a bien été créé !</p>";
					// Rediriger vers la page du chat
					Header("Location: chat.php?id_conv=".$id_conv."&conv_name=".$nom_chat);
				} else {
					echo "<p class='error'>Un ou plusieurs des utilisateurs saisis n'existe(nt) pas !</p>";
					echo "<p class='error'>Vérifiez de plus que vous n'avez pas oublié de mettre une virgule entre chaque nom d'utilisateur.</p>";
					echo "<p class='error'>Votre saisie : \"".$_POST['liste_util']."\"</p>";
				}
			}

				



		?>
	</fieldset>
</div>

</body>

<?php
include 'php/footer.php';
?>
</html>

