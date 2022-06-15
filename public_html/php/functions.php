<?php




// Fonction qui envoie un message de contenu = $message, au nom de l'utilisateur $id_util dans la conversation $id_conv. Renvoie true si la requete s'est bien passée, false sinon.
function envoyerMessage($id_util, $id_conv, $message) {
	require 'php/config.php';
	require 'php/connexiondb.php'; // Crée $linkpdo

	$date = strtotime("now");
	$sql = "INSERT INTO message (Id_utilisateur, Id_Conversation, Date_heure, Contenu) VALUES (:id_util, :id_conv, :date, :contenu)";
	$req = $linkpdo->prepare($sql);
	$req->execute(array(
		'id_util' => $_SESSION['id_util'],
		'id_conv' => $id_conv,
		'date' => $date,
		'contenu' => $message
	));
	return $req;
}
?>