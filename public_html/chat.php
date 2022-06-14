<?php
session_start();
$thisPageTitle = "Randothèque - Chat"; // Titre de l'onglet
$thisPage = "chat"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php
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

			?>
		</div>

		<form name="message" action="">
			<input name="usermsg" type="text" id="usermsg" size="63" />
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

