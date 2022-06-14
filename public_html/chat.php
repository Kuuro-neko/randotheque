<?php
session_start();
$thisPageTitle = "Randothèque - Chat"; // Titre de l'onglet
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
<div id="main">
	<div id="chat_rooms">
		<div id="menu">
			<p class="welcome">Vos groupes de chat</b></p>
			<div style="clear:both"></div>
		</div>

		<div id="roombox">
			<?php

			?>
		</div>

		<form name="createroom" action="creer_chat.php" method="get">
			<input name="createroom" type="submit"  id="createroom" value="Créer un nouveau groupe"/>
		</form>
	</div>

	<div id="wrapper">
		<div id="menu">
			<p class="welcome">Bienvenue, <b><?php echo $_SESSION['nom_util']; ?></b></p>
			<p class="logout"><a id="exit" href="#">Quitter le groupe de chat</a></p>
			<div style="clear:both"></div>
		</div>

		<div id="chatbox">
			<?php
				// Si un message a été envoyé, on le met dans la base de données avant de récupérer tous les messages !
				if(isset($_POST['submitmsg'])) {
					$date = strtotime("now");
					$sql = "INSERT INTO message (Id_utilisateur, Id_Conversation, Date_heure, Contenu) VALUES (:id_util, :id_conv, :date, :contenu)";
					$req = $linkpdo->prepare($sql);
					$req->execute(array(
						'id_util' => $_SESSION['id_util'],
						'id_conv' => $_GET['id_conv'],
						'date' => $date,
						'contenu' => $_POST['message']
					));
				}

				// Si on a ouvert un groupe de chat
				if(isset($_GET['id_conv'])) {
					// Récurérer les messages et les informations des utilisateurs de la conversation dans les tables conversation et message et utilisateur
					$id_conv = $_GET['id_conv'];
					$sql = "SELECT * FROM conversation, message, utilisateur
					WHERE conversation.Id_Conversation = :id_conv AND conversation.Id_Conversation = message.Id_Conversation AND message.Id_Utilisateur = utilisateur.Id_Utilisateur
					ORDER BY message.Date_heure ASC";
					$req = $linkpdo->prepare($sql);
					$req->execute(array(':id_conv' => $id_conv));
					$result = $req->fetchAll();
					foreach ($result as $row) {
						echo "<div class='message'>";
						echo "<p class='user'>".$row['Nom_Utilisateur']."</p>";
						echo "<p class='time'>".$row['Date_heure']."</p>";
						echo "<p class='msg'>".$row['Texte_Message']."</p>";
						echo "</div>";
					}
				}
			?>
		</div>

		<form name="message" action="chat.php
		<?php 
			if(isset($_GET['id_conv'])) {
				echo "?id_conv=".$_GET['id_conv'];
			}
		?>
		" method="post">
			<input name="message" type="text" id="usermsg" size="63" />
			<input name="submitmsg" type="submit"  id="submitmsg" value="Envoyer" />
			<button type="button" onClick="window.location.reload();">Rafraichir</button>
		</form>
		
	</div>
</div>

</body>

<?php
include 'php/footer.php';
?>
</html>

