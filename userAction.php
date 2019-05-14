<?php

if (session_status() != PHP_SESSION_ACTIVE){
	session_start();
}
//====================================================================== INSCRIPTION avec CONFIRMATION par mail
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
				// générer un token de 24 caractères
				$token = RandomString(24);
				$verify = 0;
				$dateverify = time();
				$password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);
				$sql = "INSERT INTO `users` (`lastname`, `firstname`, `address`, `zipCode`, `city`, `country`, `phone`, `mail`, `password`, `token`, `dateverify`,`verify` ) VALUES (
				 :lastname,				 
				 :firstname,
				 :address,
				 :zipCode, 
				 :city,
				 :country,
				 :phone,
				 :mail,
				 :password,
				 :token,
				 :dateverify,
				 :verify)
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
					":password"		=> $password,
					":token"		=> $token,
					":dateverify"	=> $dateverify,
					":verify"		=> $verify ]);

				if($result){
					$sql = "SELECT `id_user` FROM users WHERE `mail`= ?";
					$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
					$statement = $pdo->prepare($sql);
					$statement->execute(
						[
							htmlspecialchars($_POST["mail"])
						]
					);
					$user = $statement->fetch();
					if ($user){
						// envoyer le mail de confirmation
						$texte = "Pour confirmer votre inscription au site 'Beer Shop', veuillez cliquer sur le lien suivant : ";
						$texte .= "http://localhost/correctionChallengePHPdev/?p=verify";
						$texte .= "&id=" . $user['id_user'];
						$texte .= "&token=" . $token;
						$texte .= "&createdAt=".$dateverify;
						$texte .= "&verify=".$verify;
						$res = sendMail($_POST["mail"], "Confirmation Inscription Beer Shop",  $texte);
						if ($res){
							displayFlashMessage("Veuillez confirmer votre inscription en cliquant sur le lien qui vous a été envoyé par mail", "", "");
						}
						else{
							displayFlashMessage("", "", "Erreur d'envoi du mail de confirmation, recommencez.");
							header('location: ?p=register');

						}
					}
					//userConnect($_POST["mail"], $_POST["password"]);
				}else{
					die("pas ok : " . $result);
					//TODO : signaler erreur
				}
			}else{//fin verif user existe
				userConnect($_POST["mail"], $_POST["password"]);
			}
		}//fin verification mail et password
//====================================================================== CONNEXION
	}else 
		if(	isset($_POST["mail"]) && !empty($_POST["mail"]) &&
			isset($_POST["password"]) && !empty($_POST["password"]) &&
			isset($_POST["robot"]) && empty($_POST["robot"])
			//protection robot
		){
			userConnect($_POST["mail"], $_POST["password"]);
		}else
//====================================================================== RESET password par mail
			if ( isset($_POST["mail"]) && !empty($_POST["mail"]) ){
				$sql = 'SELECT * FROM `users` WHERE `mail` = :email';
				$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
				$statement = $pdo->prepare($sql);
				$statement->execute([':email' 	=> $_POST["mail"]]);
				$user = $statement->fetch();
				if($user){
					// générer un nouveau mot de passe à sauvegarder dans la table users
					$passwordrdn = rand();
					$password = password_hash($passwordrdn, PASSWORD_BCRYPT);
					// modification des infos du user dans la base
					$sql = 'UPDATE `users` SET `password` = :password WHERE `users`.`id_user` = :id_user ';
					$statement = $pdo->prepare($sql);
					$result = $statement->execute([
					        ':password'  => $password, 
					        ':id_user'  => $user['id_user']
					        ]);
					if ($result){
						// envoyer nouveau mot de passe
						sendMail($_POST["mail"], "Réinitialisation mdp",  "Le nouveau mot de passe est : " .  $passwordrdn);
					}else{
						// TODO signaler problème 
						die("erreur modification du password en base");
					}

				}

			}else
//====================================================================== VERIFICATION d'inscription
				if ( isset($_GET["id"]) && !empty($_GET["id"]) &&
					isset($_GET["token"]) && !empty($_GET["token"]) &&
					isset($_GET["createdAt"]) && !empty($_GET["createdAt"]) &&
					isset($_GET["verify"]) && $_GET["verify"] == 0
					){
					$sql = "SELECT * FROM users WHERE `id_user`= ?";
					$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
					$statement = $pdo->prepare($sql);
					$statement->execute(
						[
							htmlspecialchars($_GET["id"])
						]
					);
					$user = $statement->fetch();
					if ($user){
						if ($user["token"] == $_GET["token"] &&
							$user["dateverify"] == $_GET["createdAt"]
							){

							$sql = "UPDATE `users` SET `verify`=:verify WHERE `id_user`=:id_user";
							$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
							$statement = $pdo->prepare($sql);
							$result = $statement->execute([
								":verify" => true,
								":id_user" 	=> $user["id_user"]
							]);
							if ($result){
								$_SESSION['success'] = 'Votre inscription est validée, vous pouvez vous connecter';
								header('location: ?p=login');
								exit();
								//userConnect($user["mail"]);
							}
						}
						$_SESSION['error'] = "Votre inscription n'est pas validée, veuillez recommencer";
						header('location: ?p=register');
					}else{
						die('userAction : user non trouvé dans la base ');
					}

				}
//====================================================================== CONTACT
				else
					if ( isset($_POST["send"]) &&
						isset($_POST["from"]) &&
						isset($_POST["object"]) &&
						isset($_POST["message"])  
						){
						    define( 'MAIL_TO', $gmailUser);  
						    define( 'MAIL_FROM', '' ); // valeur par défaut  
						    define( 'MAIL_OBJECT', 'objet du message' ); // valeur par défaut  
						    define( 'MAIL_MESSAGE', 'votre message' ); // valeur par défaut  

						    $mailSent = false; // drapeau qui aiguille l'affichage du formulaire OU du récapitulatif  
						    $errors = array(); // tableau des erreurs de saisie  
							// si le courriel fourni est vide OU égale à la valeur par défaut  
					        $from = filter_input( INPUT_POST, 'from', FILTER_VALIDATE_EMAIL );  
					        if( $from === NULL || $from === MAIL_FROM ) 
					        {  
					            $errors[] = 'Vous devez renseigner votre adresse de courrier électronique.';  
						 		$_SESSION['error'] = 'Vous devez renseigner votre adresse de courrier électronique.';
					        }  
					        elseif( $from === false ) // si le courriel fourni n'est pas valide  
					        {  
					            $errors[] = 'L\'adresse de courrier électronique n\'est pas valide.';  
					            $from = filter_input( INPUT_POST, 'from', FILTER_SANITIZE_EMAIL );  
					        }  

					        $object = filter_input( INPUT_POST, 'object', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW );  
					        if( $object === NULL OR $object === false OR empty( $object ) OR $object === MAIL_OBJECT ) // si l'objet fourni est vide, invalide ou égale à la valeur par défaut  
					        {  
					            $errors[] = 'Vous devez renseigner l\'objet.';  
					        }  

					        $message = filter_input( INPUT_POST, 'message', FILTER_UNSAFE_RAW );  
					        // si le message fourni est vide ou égal à la valeur par défaut  
					        if( $message === NULL OR $message === false OR empty( $message ) OR $message === MAIL_MESSAGE ) 
					        {  
					            $errors[] = 'Vous devez écrire un message.';  
					        }  

					        if( count( $errors ) === 0 ) // si il n'y a pas d'erreur  
					        {  
					        	// tentative d'envoi du message  
					            if(sendMail(MAIL_TO, $object, $message)){
					            //if( mail( MAIL_TO, $object, $message, "From: $from\nReply-to: $from\n" ) ) 
					              
					                $mailSent = true;  
					 
					            }  
					            else // échec de l'envoi  
					            {  
					                $errors[] = 'Votre message n\'a pas été envoyé.';  
					            }  
					        }  

						    if( $mailSent === true ) // si le message a bien été envoyé, on affiche le récapitulatif  
						    {  
						 		$_SESSION['success'] = 'Votre message a bien été envoyé.';
							    echo    '<p id="success">Votre message a bien été envoyé.</p>  ';
							    echo    '<p><strong>Courriel pour la réponse :</strong><br />' . $from . '</p>';  
							    echo    '<p><strong>Objet :</strong><br />' . $object . '</p>'  ;
							    echo    '<p><strong>Message :</strong><br />' . nl2br( htmlspecialchars( $message ) ) . '</p>';  
						    }  
						    else // le formulaire est affiché pour la première fois ou le formulaire a été soumis mais contenait des erreurs  
						    {  
						        if( count( $errors ) !== 0 )  
						        {  
						            echo( "\t\t<ul>\n" );  
						            foreach( $errors as $error )  
						            {  
						                echo( "\t\t\t<li>$error</li>\n" );  
						            }  
						            echo( "\t\t</ul>\n" );  
						 			$_SESSION['error'] = 'il y a des erreurs.';
						        }  
						        else  
						        {  
						        	$_SESSION['error'] = "Tous les champs sont obligatoires...";
						            /*echo( "\t\t<p id=\"welcome\"><em>Tous les champs sont obligatoires</em></p>\n" );  */
						        } 
						    } 
		              		//header('location: ?p=contact');
//====================================================================== RIEN
						}else{
						    $_SESSION['error'] = "userAction bac à sable";

							// si rien
							//die('userAction bac à sable');
		              		// header('location: ?p=home');
						}

//require 'includes/footer.php';
