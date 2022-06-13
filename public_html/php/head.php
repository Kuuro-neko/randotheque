<div class="enTete">
	
	<div class="titreEtBoutons">
		<div class="image">
			<img class="logo" src="images/logo.png" alt="Logo de l'application randothèque" height="50">
		</div>
		<div class="titre">
			<h1>Randothèque</h1>
		</div>
		<div class="boutons">
			<nav class="menu">
				<input type="checkbox" id="menuToggle">
				<label for="menuToggle" class="menu-icon"><img src="./images/menu_icon.png" alt="Menu de navigation déroulant" height="60"></label>
				<ul>
					<li><a href="acceuil.php">Accueil</a>
					</li>
					<li><a href="recherche.php">Recherche</a>
					</li>
					<li><a href="import.php">Importer</a>
					</li>
					<li><a href="profil.php?id_util=<?php echo $_SESSION['id_util']; ?>">Mon profil</a>
					</li>
					<li><a href="chat.php">Chat</a>
					</li>
					<li><a href="apropos.php">À propos</a>
					</li>
					<li><a href="acceuil.php?disconnect=true">Déconnexion</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>