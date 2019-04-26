<?php
require_once 'includes/function.php';



// foreach ($_POST as $key => $value) {
// 	$$key = $value;
//  c'est égale a :
//  $lastname = $value;
// }
// var_dump($lastname);

if(!empty($_POST)){
	if(	isset($_POST["lastname"]) &&
		isset($_POST["firstname"]) &&
		isset($_POST["address"]) &&
		isset($_POST["zipCode"]) &&
		isset($_POST["city"]) &&
		isset($_POST["country"]) &&
		isset($_POST["phone"]) &&
		isset($_POST["mail"]) &&
		isset($_POST["mailVerify"]) &&
		isset($_POST["password"]) &&
		isset($_POST["passwordVerify"])
	){
		if(
			( $_POST["mail"] == $_POST["mailVerify"]) &&
			( $_POST["password"] == $_POST["passwordVerify"])
		){
			$sql = "SELECT * FROM users WHERE `mail`= ?";
			$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
			$statement = $pdo->prepare($sql);
			$statement->execute([$_POST["mail"]]);
			$user = $statement->fetch();
		
			if(!$user){
				$password = password_hash($_POST["password"], PASSWORD_BCRYPT);
				$sql = "INSERT INTO `users` (`lastname`, `firstname`, `address`, `zipCode`, `city`, `country`, `phone`, `mail`, `password`) VALUES (
				 :lastname,
				 :firstname,
				 :address,
				 :zipCode, 
				 :city,
				 :country,
				 :phone,
				 :mail,
				 :password)
				 ";
				$statement = $pdo->prepare($sql);
				$result = $statement->execute([
					":lastname"		=> $_POST["lastname"],
					":firstname"	=> $_POST["firstname"],
					":address"		=> $_POST["address"],
					":zipCode"		=> $_POST["zipCode"],
					":city"			=> $_POST["city"],
					":country"		=> $_POST["country"],
					":phone"		=> $_POST["phone"],
					":mail"			=> $_POST["mail"],
					":password"		=> $password
				]);
				if($result){
					die("ok");
					//rediriger sur page profil
				}else{
					die("pas ok");
					//signaler erreur
				}
			}//fin verif user existe


		}//fin verification mail et password

	}//fin champ tous définis


}// fin if post




//debut html
require 'includes/header.php';

echo 	'<h1>Inscription</h1>'.
		'<form method="POST" action="">'.
 		input("lastname", "votre nom").
 		input("firstname", "votre prénom").
 		input("address", "votre adresse").
 		input("zipCode", "votre code postal").
 		input("city", "votre ville").
 		input("country", "votre pays").
 		input("phone", "votre numéro de portable", "tel").
  		input("mail", "votre courriel", "email").
  		input("mailVerify", "vérification de votre courriel", "email").
  		input("password", "votre mot de passe", "password").
  		input("passwordVerify", "confirmez votre mot de passe", "password").
  		"<button type=\"submit\">Envoyez</button>".
  		'</form>';









require 'includes/footer.php';