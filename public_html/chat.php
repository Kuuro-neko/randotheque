<?php
session_start();
date_default_timezone_set('Europe/Paris');
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

if(isset($_POST['quit'])) {
	$id_conv = $_POST['id_conv'];
	$sql = "DELETE FROM participer WHERE Id_Conversation = :id_conv AND Id_Utilisateur = :id_util";
	$req = $linkpdo->prepare($sql);
	$req->execute(array('id_conv' => $id_conv, 'id_util' => $_SESSION['id_util']));

	header('Location: chat.php');
}
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
				// On récupère les groupes de chat de l'utilisateur
				$sql = "SELECT * FROM participer, conversation WHERE participer.Id_Utilisateur = :util AND participer.Id_Conversation = conversation.Id_Conversation";
				$req = $linkpdo->prepare($sql);
				$req->execute(array('util' => $_SESSION['id_util']));
				while($resultat = $req->fetch()) {
					$currentRoom = "";
					$disablelink = "";
					if(isset($_GET['id_conv'])) {
						if($_GET['id_conv'] == $resultat['Id_Conversation']) {
							$currentRoom = "current";
							$disablelink = "style=\"pointer-events: none\"";
						}
					}
					echo "<a href=\"chat.php?id_conv=".$resultat['Id_Conversation']."&conv_name=".$resultat['Libelle']."\" ".$disablelink."><div class='".$currentRoom."room'>";
					echo "<p class='".$currentRoom."chatroom'><strong>".$resultat['Libelle']."</strong></p>";
					// Récupérer le nombre d'utilisateurs dans le groupe
					$sql2 = "SELECT count(Id_Utilisateur) as Nb_Util FROM participer WHERE Id_Conversation = :conv";
					$req2 = $linkpdo->prepare($sql2);
					$req2->execute(array('conv' => $resultat['Id_Conversation']));
					$resultat2 = $req2->fetch();
					echo "<p class='".$currentRoom."chatroom'> avec ".$resultat2['Nb_Util']." utilisateurs</p></br>";
					if($currentRoom == "current") {
						echo "<p class='".$currentRoom."chatroom'>Vous avez ouvert ce groupe de chat</p>";
					}
					echo "</div></a>";
				}
			?>
		</div>
		<form name="createroom" action="creer_chat.php">
			<input name="createroom" type="submit"  id="createroom" value="Créer un nouveau groupe"/>
		</form>
	</div>

	<div id="wrapper">
		<div id="menu">
			<p class="welcome">Bienvenue, <b><?php echo $_SESSION['nom_util']; ?></b>
			<?php 
				if(isset($_GET['id_conv'])) {
					echo " dans le groupe de chat <b>".$_GET['conv_name']."</b>";
				}
			?>
			</p>
			<?php 
				if(isset($_GET['id_conv'])) {
			?>
				<form name="quit" method="post" action="chat.php">
					<input type="submit" id="exit" Onclick="confirmGroupQuit()" value="Quitter le groupe de chat" name="quit">
					<input type="hidden" name="id_conv" value="<?php echo $_GET['id_conv']; ?>">
				</form>
			<?php
				}
			?>
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
					ORDER BY message.Date_heure DESC";
					$req = $linkpdo->prepare($sql);
					$req->execute(array(':id_conv' => $id_conv));
					$result = $req->fetchAll();
					foreach ($result as $row) {
						echo "<div class='message'>";
						echo "<p class='msg'>".date("H:i",$row['Date_heure']);
						if($row['Id_Utilisateur'] == $_SESSION['id_util']) {
							echo " <strong>".$row['Nom_d_utilisateur']."</strong>";
						}
						else {
							echo " <a class=\"otherutil\" href=\"profil.php?id_util=".$row['Id_Utilisateur']."\">".$row['Nom_d_utilisateur']."</a>";
						}
						// Si le contenu n'est pas de la forme [trace=x]
						if(strpos($row['Contenu'], "[trace=") !== false) {
							// Récupérer l'id de la trace (x dans le message de forme [trace=x])
							$trace = substr($row['Contenu'], 7, -1);
							echo " a envoyé une trace GPX. Cliquez dessus pour la visualiser : </p>";
							// Récupérer les informations sur la trace
							$sql2 = "SELECT * FROM fichier_gpx WHERE Id_Fichier_GPX = :id_gpx";
							$req2 = $linkpdo->prepare($sql2);
							$req2->execute(array(':id_gpx' => $trace));
							echo "<a href=\"\"><div class=\"trace\">";
							if($gpx = $req2->fetch()) {
								echo "<p class='gpxinfo'><strong>Type de sport</strong> : ".$gpx['Type_de_sport']."</p>";
								echo "<p class='gpxinfo'><strong>Localisation</strong> : ".$gpx['Localisation']."</p>";
								echo "<p class='gpxinfo'><strong>Difficulté</strong> : ".$gpx['Difficulte']." / 5</p>";
								echo "<p class='gpxinfo'><strong>Description</strong> : ".$gpx['Description']."</p>";

							} else {
								echo "<p class='gpxerror'>La trace n'existe pas ou a été supprimée</p>";
							}
							echo "</div></a>";


						} else {
							echo " : ".$row['Contenu']."</p>";
						}
						echo "</div>";
					}
				}
			?>
		</div>

		<form name="message" action="chat.php
		<?php 
			if(isset($_GET['id_conv'])) {
				echo "?id_conv=".$_GET['id_conv']."&conv_name=".$_GET['conv_name'];
			}
		?>
		" method="post">
			<input name="message" type="text" id="usermsg" size="63" />
			<input name="submitmsg" type="submit"  id="submitmsg" value="Envoyer" <?php if(!isset($_GET['id_conv'])) { echo 'disabled="disabled"'; } ?>/>
			<?php 
				if(isset($_GET['id_conv'])) {
					?>
					<button onclick="window.location.href='/chat.php?id_conv=<?php echo $_GET['id_conv']?>'">Rafraichir</button>
					<?php 
				}
			?>
			
		</form>
		
	</div>
</div>

</body>

<script>
function confirmGroupQuit() {
  let text = "Voulez vous vraiment quitter ce groupe de chat ?";
  return(confirm(text));
}
</script>

<?php
include 'php/footer.php';
?>
</html>

