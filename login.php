<?php
require_once 'includes/function.php';



if(!empty($_POST)){
	if(	isset($_POST["mail"]) && !empty($_POST["mail"]) &&
		isset($_POST["password"]) && !empty($_POST["password"]) &&
		isset($_POST["robot"]) && empty($_POST["robot"])//protection robot
	){

		userConnect($_POST["mail"], $_POST["password"]);
	}else{
		die('bac à sable');
	}
}





require 'includes/header.php';

echo 	'<h1>login</h1>'.
		'<form method="POST" name="inscription" action="">'.
  		input("mail", "votre courriel","", "email").
  		input("password", "votre mot de passe","", "password").
  		input("robot", "","", "hidden").
  		"<button type=\"submit\">Envoyez</button>".
  		'</form>';









require 'includes/footer.php';