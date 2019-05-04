<?php
require_once 'includes/function.php';

// foreach ($_POST as $key => $value) {
// 	$$key = $value;
//  c'est égal à :
//  $lastname = $value;
// }
if(!empty($_POST)){
	if(	isset($_POST["lastname"]) && !empty($_POST["lastname"]) &&
		isset($_POST["firstname"]) && !empty($_POST["firstname"]) &&
		isset($_POST["address"]) && !empty($_POST["address"]) &&
		isset($_POST["zipCode"]) && !empty($_POST["zipCode"]) &&
		isset($_POST["city"]) && !empty($_POST["city"]) &&
		isset($_POST["country"]) && !empty($_POST["country"]) &&
		isset($_POST["phone"]) && !empty($_POST["phone"]) &&
		isset($_POST["mail"]) && !empty($_POST["mail"]) &&
		isset($_POST["mailVerify"]) && !empty($_POST["mailVerify"]) &&
		isset($_POST["password"]) && !empty($_POST["password"]) &&
		isset($_POST["passwordVerify"]) && !empty($_POST["passwordVerify"])&&
		isset($_POST["robot"]) && empty($_POST["robot"])//protection robot
	){
		
		if(
			( 	filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL) && 
				$_POST["mail"] == $_POST["mailVerify"]
			) &&
			( $_POST["password"] == $_POST["passwordVerify"])
		){

			$sql = "SELECT * FROM users WHERE `mail`= ?";
			$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
			$statement = $pdo->prepare($sql);
			$statement->execute(
				[
					htmlspecialchars($_POST["mail"])
				]
			);
			$user = $statement->fetch();
		
			if(!$user){
				$password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
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
					":lastname"		=> htmlspecialchars($_POST["lastname"]),
					":firstname"	=> htmlspecialchars($_POST["firstname"]),
					":address"		=> htmlspecialchars($_POST["address"]),
					":zipCode"		=> htmlspecialchars($_POST["zipCode"]),
					":city"			=> htmlspecialchars($_POST["city"]),
					":country"		=> htmlspecialchars($_POST["country"]),
					":phone"		=> htmlspecialchars($_POST["phone"]),
					":mail"			=> htmlspecialchars($_POST["mail"]),
					":password"		=> $password
				]);
				if($result){
					userConnect($_POST["mail"], $_POST["password"]);
				}else{
					die("pas ok");
					//TODO : signaler erreur
				}
			}else{//fin verif user existe
				userConnect($_POST["mail"], $_POST["password"]);
			}
		}//fin verification mail et password

	}else{//fin champ tous définis
		die('bac a sable');//securisation
	}

}// fin if post




//debut html
require 'includes/header.php';

echo 	'<h1>Inscription</h1>'.
		'<form method="POST" name="inscription" action="" class="formProfil">'.
 		input("lastname", "votre nom","").
 		input("firstname", "votre prénom","").
 		input("address", "votre adresse","").
 		input("zipCode", "votre code postal","").
 		input("city", "votre ville","").
 		input("country", "votre pays","").
 		input("phone", "votre numéro de portable","", "tel").
  		input("mail", "votre courriel","", "email").
  		input("mailVerify", "vérification de votre courriel","", "email").
  		input("password", "votre mot de passe","", "password").
  		input("passwordVerify", "confirmez votre mot de passe","", "password").
  		input("robot", "","", "hidden").
  		"<button type=\"submit\">Envoyer</button>".
  		'</form>';

require 'includes/footer.php';



