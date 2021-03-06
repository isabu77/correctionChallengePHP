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
	global $racine; //Permet de récupérer une variable externe à la fonction  
	// avec DOCKER : remplacer "http://" par $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://'
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

		//on continue sur index.php avec affichage erreur pas connecté
		if ($user && $user['verify'] == false)
		{
			$_SESSION['error'] = "Votre inscription n'est pas validée, veuillez recommencer.";
		}
		else{
    		$_SESSION['error'] = "Adresse mail ou mot de passe invalide";
		}
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

/**
* envoi d'un mail par swift_mailer 
* @return int nb de mails envoyés
*/
function sendMail($emailTo, $sujet, $msg, $cci = true, $from="")//:int
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

	// Crée le message en HTML et texte
	$message = new Swift_Message($sujet);
	$message->setFrom([$gmailUser => $pseudo]);

	if ($cci){
		$message->setBcc($mailTo);
	}else{
		$message->setTo($mailTo);
	}
	
	if (is_array($msg) && array_key_exists('text', $msg) && array_key_exists('html', $msg)){
		$message->setBody($msg['html'] ,'text/html' );
		$message->addPart($msg['text'] ,'text/plain' );
	}else if ( is_array($msg) && array_key_exists('html', $msg)){
		$message->setBody($msg["html"], 'text/html');
		$message->addPart($msg["html"], 'text/plain');
	}elseif (is_array($msg) && array_key_exists("text", $msg)) {
		$message->setBody($msg["text"], 'text/plain');

	}elseif (is_array($msg)) {
		die('erreur une clé n\'est pas bonne');

	}else{
		$message->setBody($msg, 'text/plain');
	}

	if (!empty($from)){
		// ajouter un Header
		$headers = $message->getHeaders();
		// "From: $from\nReply-to: $from\n"
		$headers->addMailboxHeader('From', [$from]);
		$headers->addMailboxHeader('Reply-to', [$from]);
	}

	// envoie le message
	return($mailer->send($message));
}

/**
* afficher un message FLASH  
* @return void
*/
function displayFlashMessage($info = "", $succes = "", $erreur = ""){

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

/**
* créer une chaine "token" 
* @return string
*/
function RandomString($lg = 24)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = "";
    for ($i = 0; $i < $lg; $i++) {
        $randstring .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return ($randstring);
}


