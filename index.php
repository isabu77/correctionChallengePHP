<?php 
require_once 'includes/function.php';

// inscription ou connexion ? userAction traite aussi le contact et le reset de mdp
if(!empty($_POST) || (!empty($_GET) && isset($_GET["verify"])) ){
	require 'userAction.php';
}

if(!isset($_GET["p"])){
	header('location:?p=home');
	exit();
}else{
	// quelle page faut-il afficher ?
	$page= htmlspecialchars(strtolower($_GET['p']));

	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}
	// on teste le deconnect avant d'afficher le header 
	// car le contenu du menu dépend de la connexion 
	if ($page == 'deconnect'){
		unset($_SESSION["auth"]);
	}
	// inclusion du HEADER contenant le MENU
	include 'includes/header.php';

	// affichage des messages FLASH définis dans userAction sous le header
	$erreurs = "";
	$succes = "";
	if(isset($_SESSION['error'])) {
		$erreurs = $_SESSION['error'];
		if (is_array($_SESSION['error'])){
			$erreurs = implode(' ', $_SESSION['error']);
		}
		unset($_SESSION["error"]); //Supprime la SESSION['success']
	}
	if(isset($_SESSION['success'])) {
		$succes = $_SESSION['success'];
		if (is_array($_SESSION['success'])){
			$succes = implode(' ', $_SESSION['success']);
		}
		unset($_SESSION["success"]); //Supprime la SESSION['success']
	}
	displayFlashMessage("", $succes, $erreurs);
	
	// mini router des pages
	switch($page){
		case 'login':
		case 'register':
		case 'reset':
			require 'userForm.php'; 
			break;
		case 'boutique':
			require 'boutique.php';
			break;
		case 'purchase':
			require 'purchase_order.php';
			break;
		case 'profil':
			require 'profil.php';
			break;
		case 'contact':
			require 'contact.php';
			break;
		case 'deconnect':
		case 'home':
	//}
//}else{

?>
	<section class="sectionHome">
		<h1>Bread Beer Shop</h1>
		<h2>Welcome!</h2>
		<article class="articleHome">
			<div>
				<img src="<?= uri("assets/img/BAP.jpg") ?>" alt="BAP logo">
			</div>
			<p>Gros producteur de bières, j'ai créé ma propre bière spéciale : "du pain dur à la bière".<br />
			Lancée dans un village en Allemagne, "Stuttgart la rieuse". Dans un rayon de 10 km, les boulangeries nous fournissent le pain dur et font la promotion de cette bière.<br />
			La bière au pain est seulement disponible par contact email, à 15 € les 50 cl. <br /> 
			</p>
		</article>
		<article class="articleHome">
			<div>
				<img src="<?= uri("assets/img/BAP.jpg") ?>" alt="BAP logo">
			</div>
			<p>OFFRE EXCEPTIONNELLE de LANCEMENT :<br /> 3 bières offertes pour une bière achetée.<br />
			</p>
		</article>
	</section>
	<?php 
			break;
		default:
			require '404.php';
	} // switch
	include 'includes/footer.php';
}
?>