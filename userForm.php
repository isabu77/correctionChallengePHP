<h1 class="titreduhaut">Bread Beer Shop - <?= ($page == 'login') ? "Connexion" : "Inscription" ?></h1>
<form method='POST' name="<?= $page?>" action="" class="formProfil">

<?php
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


