<div class="enTete">
	
	<div class="titreEtBoutons">
		<div class="image">
			<img class="logo" src="images/logo.png" alt="Logo de l'application randothèque" height="50">
		</div>
		<div class="titre">
			<h1>Randothèque</h1>
		</div>
		<div class="boutons">
			<div class="navigation">
				<input class="btMenu" type="button" onclick="location.href='acceuil.php';" value="Acceuil"/>
				<input class="btMenu" type="button" onclick="location.href='recherche.php';" value="Recherche"/>
				<input class="btMenu" type="button" onclick="location.href='import.php';" value="Importer"/>
				<input class="btMenu" type="button" onclick="location.href='profil.php?id_util=<?php echo $_SESSION['id_util']; ?>';" value="Mon profil"/>
				<input class="btMenu" type="button" onclick="location.href='chat.php';" value="Chat"/>
				<input class="btMenu" type="button" onclick="location.href='apropos.php';" value="A propos"/>
			</div>
			<div class="deconnexion">
				<form action="" method="get">
					<input type="submit" name="disconnect" value="Déconnexion" class="btDeco">
				</form>
			</div>
		</div>
	</div>
</div>