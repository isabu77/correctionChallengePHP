<?php
require_once 'config.php';

/**
* retourne le nom du dossier
*
* @return string
*/
function uri($cible="")//:string
{
	$uri = "http://".$_SERVER['HTTP_HOST'];
	$folder = basename(dirname(dirname(__FILE__)));
	return $uri.'/'.$folder.'/'.$cible;
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
    	return new PDO($dsn, $dbuser, $dbpassword);
	} catch (PDOException $e) {
    	echo 'Connexion échouée : ' . $e->getMessage();
    	die();
	}
}


/**
*	génère un champ de formulaire de type input
*	@return String
*/

function input($name, $label, $type='text', $require=false)//:string
{
	$input = "<div class=\"form-group\"><label for=\"".
	$name."\">".$label.
	"</label><input id=\"".
	$name."\" type=\"".$type.
	"\" name=\"".$name."\" value=\"\" ";
	$input .= ($require)? "required": "";
	$input .= "></div>";

	return $input;
}

/**
* Connect le client
* @return void
*/
function userConnect($mail, $password){//:void
	require_once 'config.php';

	$sql = "SELECT * FROM users WHERE `mail`= ?";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);

		$statement = $pdo->prepare($sql);
		$statement->execute([htmlspecialchars($mail)]);
		$user = $statement->fetch();
		
		if(	$user && 
			password_verify(
			htmlspecialchars($password), $user['password']
		)){
				if (session_status() != PHP_SESSION_ACTIVE){
					session_start();
				}
				unset($user['password']);
				$_SESSION['auth'] = $user;
				var_dump($_SESSION);
				die();
				//header('location: profil.php');
				//connecté

		}else{
			if (session_status() != PHP_SESSION_ACTIVE){
					session_start();
				}
			$_SESSION['auth'] = false;
			header('location: login.php');
			//pas connecté
		}
}
	





