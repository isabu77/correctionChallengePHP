<h1 class="titreduhaut"><?= ($page == 'login') ? "Bread Beer Shop - Connexion" : ($page == 'register' ? "Bread Beer Shop - Inscription" : "Bread Beer Shop - Réinitialisation du mot de passe")?></h1>
<form method='POST' name="<?= $page?>" action="" class="formProfil">
<?php
	if ($page == 'reset'){
		echo '<p>Pour récupérer votre compte, saisissez votre adresse mail de connexion pour recevoir votre mot de passe</p>';
	}
	echo input("mail", "votre courriel","", "email");

if ($page == 'login'){
  	echo	 input("password", "votre mot de passe","", "password");

}else if ($page == 'register'){
//debut html
//require 'includes/header.php';

	echo 	
  		input("mailVerify", "vérification de votre courriel","", "email").
 		input("lastname", "votre nom","").
 		input("firstname", "votre prénom","").
 		input("address", "votre adresse","").
 		input("zipCode", "votre code postal","").
 		input("city", "votre ville","").
 		input("country", "votre pays","").
 		input("phone", "votre numéro de portable","", "tel").
  		input("password", "votre mot de passe","", "password").
  		input("passwordVerify", "confirmez votre mot de passe","", "password");
}
	echo input("robot", "","", "hidden");
?>
	<button type="submit">Envoyer</button>
	<?php if ($page == 'login'){
		echo '<a href="?p=reset">mot de passe oublié</a>';
	}
?></form>


