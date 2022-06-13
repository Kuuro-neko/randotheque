<?php
session_start();
$thisPageTitle = "Randothèque - Chat"; // Titre de l'onglet
$thisPage = "chat"; // Pour lier à la bonne feuille CSS

include 'php/deconnexion_utilisateur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Chat - Customer Module</title>
	<link type="chat/css" rel="stylesheet" href="chat.css" />
</head>

<?php
include 'php/balise_head.php';
echo "<body>";
include 'php/head.php';
?>

<p>Chat avec d'autres utilisateurs</p> <!-- miaou -->
<div id="wrapper">
	<div id="menu">
		<p class="welcome">Bienvenue, <b><?php echo $_SESSION['nom_util']; ?></b></p>
		<p class="logout"><a id="exit" href="#">Quitter le chat</a></p>
		<div style="clear:both"></div>
	</div>

	<div id="chatbox">
		<?php
		if(file_exists("log.html") && filesize("log.html") > 0){
			$handle = fopen("log.html", "r");
			$contents = fread($handle, filesize("log.html"));
			fclose($handle);

			echo $contents;
		}
		
		?>
	</div>

	<form name="message" action="">
		<input name="usermsg" type="text" id="usermsg" size="63" />
		<input name="submitmsg" type="submit"  id="submitmsg" value="Envoyer" />
	</form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function()
{
	//If user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Voulez vous vraiment quitter le chat?");
		if(exit==true){window.location = 'index.php?logout=true';}		
	});


	//If user submits the form
	$("#submitmsg").click(function(){	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});

	//Load the file containing the chat log
	function loadLog(){		

		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div				
		  	},
		});
	}

	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div	
				
				//Auto-scroll			
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}				
		  	},
		});

	}
	setInterval (loadLog, 2500);

});
</script>

</body>

<?php
include 'php/footer.php';
?>
</html>

