<?php
require_once 'includes/function.php';

$user = userOnly();


if(!empty($_POST)){
	//verif pour modif mdp
	if(	isset($_POST["passwordOld"]) && !empty($_POST["passwordOld"]) &&
		isset($_POST["password"]) && !empty($_POST["password"]) &&
		isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"]) &&
		isset($_POST["robot"]) && empty($_POST["robot"])//protection robot
	){
		if(userConnect($user["mail"], $_POST["passwordOld"], true)){
			if ($_POST["password"] == $_POST["passwordVerify"]) {
				$password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
				$sql = "UPDATE `users` SET `password`=:password WHERE `id_user`=:id_user";
				$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
				$statement = $pdo->prepare($sql);
				$statement->execute([
					":password" => $password,
					":id_user" 	=> $user["id_user"]
				]);
				//message modif ok
			}else{
				//mdp correspondent pas
			}
		}else{
			//erreur 
		}
	}else{
		die('bac à sable');
	}
	//verif pour modif profil
	/*
	if(isset($_POST["lastname"]) && !empty($_POST["lastname"]) &&
		isset($_POST["firstname"]) && !empty($_POST["firstname"]) &&
		isset($_POST["address"]) && !empty($_POST["address"]) &&
		isset($_POST["robot"]) && empty($_POST["robot"])//protection robot){
	
		sauvegarde en bdd
	}else{
		erreur
	}
	*/
}

$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
$sql = "SELECT * FROM orders WHERE id_user = ?";
$statement = $pdo->prepare($sql);
$statement->execute([$user["id_user"]]);
$orders = $statement->fetchAll();
require 'includes/header.php';

echo 	'<h1>Profil</h1>';

echo	'<hr /><form method="POST" name="inscription" action="">'.
 		input("lastname", "votre nom",$user["lastname"]).
 		input("firstname", "votre prénom",$user["firstname"]).
 		input("address", "votre adresse",$user["address"]).
 		input("zipCode", "votre code postal",$user["zipCode"]).
 		input("city", "votre ville",$user["city"]).
 		input("country", "votre pays",$user["country"]).
 		input("phone", "votre numéro de portable",$user["phone"], "tel").
  		"votre courriel : ".$user["mail"].
  		input("robot", "","", "hidden").
  		input("id_user", "",$user["id"], "hidden").
  		"<button type=\"submit\">Envoyez</button>".
  		'</form><hr />';

echo 	'<form method="POST" name="inscription" action="">'.
  		input("passwordOld", "votre ancien mot de passe","", "password").
  		input("password", "votre mot de passe","", "password").
  		input("passwordVerify", "confirmez votre mot de passe","", "password").
  		input("robot", "","", "hidden").
  		"<button type=\"submit\">Envoyez</button>".
  		'</form><hr />';

//tableau des commandes
foreach ($orders as $order) {
	echo '<a href="'.uri("confirmationDeCommande.php?id=").$order["id"].'">commande n°'.$order["id"].'- '.$order["priceTTC"].'€ </a><br />';
}

require 'includes/footer.php';