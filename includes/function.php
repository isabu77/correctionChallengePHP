<?php
// charge le contenu de composer.json : swiftmailer et php-flash-messages
require_once 'vendor/autoload.php';
require_once 'config.php';
date_default_timezone_set('Europe/Paris');

/**
* retourne le nom du dossier
*
* @return string
*/
function uri($cible="")//:string
{
	global $racine; //Permet de récupérer une variable externe à la fonction  $_SERVER['HTTP_X_FORWARDED_PROTO']
	$uri = "http://" . $_SERVER['HTTP_HOST']; 
	$folder = "";
	if(!$racine) {
		$folder = basename(dirname(dirname(__FILE__))).'/'; //Dossier courant
	}
	return $uri.'/'.$folder.$cible;
}


/**
* crée une connexion à la base de données
*	@return \PDO
*/

function getDB(	$dbuser='root', 
				$dbpassword='', 
				$dbhost='localhost',
				$dbname='sitebeer') //:\PDO
{
	

	$dsn = 'mysql:dbname='.$dbname.';host='.$dbhost.';charset=UTF8';
	try {
    	$pdo = new PDO($dsn, $dbuser, $dbpassword);

    	//definit mode de recupération en mode tableau associatif
    	// $user["lastname"];
    	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    	//definit mode de recupération en mode Objet
    	//$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    	// $user->lastname;
    	return $pdo;

	} catch (PDOException $e) {
    	echo 'Connexion échouée : ' . $e->getMessage();
    	die();
	}
}


/**
*	génère un champ de formulaire de type input
*	@return String
*/

function input($name, $label,$value="", $type='text', $require=true)//:string
{
	$input = "<div class=\"form-group\"><label for=\"".
	$name."\">".$label.
	"</label><input id=\"".
	$name."\" type=\"".$type.
	"\" name=\"".$name."\" value=\"".$value."\" ";
	$input .= ($require)? "required": "";
	$input .= "></div>";

	return $input;
}

/**
* Connect le client
* @return boolean|void
*/
function userConnect($mail, $password = "", $verify=false){//:boolean|void
	require 'config.php';

	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}
	$sql = "SELECT * FROM users WHERE `mail`= ?";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);

		$statement = $pdo->prepare($sql);
		$statement->execute([htmlspecialchars($mail)]);
		$user = $statement->fetch();
		if(	$user && !empty($password) &&
			$user['verify'] == true &&
			password_verify(htmlspecialchars($password), $user['password'])
		){
			if($verify){
				return true;
				//exit();
			}

			unset($user['password']);
			$_SESSION['auth'] = $user;
			//connecté
			header('location: profil.php');
			exit();

		}else{

			if($verify){
				return false;
				//exit();
			}
			$_SESSION['auth'] = false;

			if ($user['verify'] == false)
			{
				$_SESSION['error'] = "Votre inscription n'est pas validée, veuillez recommencer.";
				header('location: ?p=login');
			}
			//TODO : err pas connecté
		}

}



/**
* verifie que l'utilisateur est connecté
* @return array|void
*/
function userOnly($verify=false){//:array|void|boolean
	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}
	// n'est pas defini et false
	if(!isset($_SESSION["auth"]) || !$_SESSION["auth"]){
		if($verify){
			return false;
		//exit();
		}
		header('location: userForm.php');
		exit();
	}
	return $_SESSION["auth"];
}

/* envoi d'un mail par swift_mailer */
function sendMail($emailTo, $sujet, $msg, $cci = true)
{
require 'config.php';

	$mailTo = $emailTo;
	if (!is_array($emailTo)){
		$mailTo = [$emailTo];
	}
	// Crée le Transport
	$transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
	$transport->setUsername($gmailUser);
	$transport->setPassword($gmailpwd);
	

	// Crée le Mailer utilisant le Transport
	$mailer = new Swift_Mailer($transport);

	// Crée le message en HTML
	$message = new Swift_Message($sujet);
	$message->setFrom([$gmailUser => "Isabu77"]);

	if ($cci){
		$message->setBcc($mailTo);
	}else{
		$message->setTo([$mailTo]);
	}
	
/*	if (is_array($msg) && array_key_exists('text', $msg) && array_key_exists('html', $msg)){
			$message->setBody($msg['html'] ,'text/html' );
			$message->addPart($msg['text'] ,'text/plain' );
	}else if ( is_array($msg) && array_key_exists('html', $msg)){

	}
*/
	if (!is_array($msg)){
		$message->setBody($msg ,'text/plain');
		$message->addPart('<html>' .
				' <body>' .
				$msg .
				' </body>' .
				'</html>',
				  'text/html' );
	}else{
		if (array_key_exists('text', $msg)){
			$message->setBody($msg['text'] ,'text/plain' );
		}
		if (array_key_exists('html', $msg)){
			$message->addPart($msg['html'] ,'text/html' );
		}
	}

	// en copie :
/*	if (!empty($emailCc)){
		if (!is_array($emailCc)){
			$message->setCc([$emailCc]);
		}else{
			$message->setCc($emailCc);
		}
	}
*/
	// en copie cachée :
/*	if (!empty($emailCci)){
		if (!is_array($emailCci)){
			$message->setBcc([$emailCci]);
		}else{
			$message->setBcc($emailCci);
		}
	}
*/
	// envoie le message
	$result = $mailer->send($message);

	return($result);

}

// afficher un message FLASH :
function displayFlashMessage($info, $succes = "", $erreur = ""){

	// php-flash-messages fonctionne avec bootstrap !!
	// Start a Session
	if (!session_id()) @session_start();
		
	// Instantiate the class
	$msg = new \Plasticbrain\FlashMessages\FlashMessages();

	// Add messages
	if (!empty($succes)){
		$msg->success($succes, null, true);
	}	

	if (!empty($erreur)){
		$msg->error($erreur, null, true);
	}

	if (!empty($info)){
		$msg->info($info, null, true);
	}
		
	// affichage
	$msg->display();

}


function RandomString($lg)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = [];
    for ($i = 0; $i < $lg; $i++) {
        $randstring[$i] = $characters[rand(0, strlen($characters))];
    }
    return implode('', $randstring);
}


