<?php 
require_once 'includes/function.php';

// inscription ou connexion ?
if(!empty($_POST)){
	require 'userAction.php';
}

if(!isset($_GET["p"])){
	header('location:?p=home');
	exit();
}else{
	$page= htmlspecialchars(strtolower($_GET['p']));
	if ($page == 'deconnect'){
		if (session_status() != PHP_SESSION_ACTIVE){
			session_start();
		}
		unset($_SESSION["auth"]);
	}
	include 'includes/header.php';
	// mini router des pages
	switch($page){
		case 'login':
		case 'register':
		case 'reset':
			include 'userForm.php'; 
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