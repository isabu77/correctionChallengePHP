<?php
require_once 'function.php';
$connect = userOnly(true);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Bread Beer Shop</title>
	<link rel="stylesheet" type="text/css" href="<?= uri("assets/css/styles.css") ?>">
	<link rel="stylesheet" type="text/css" href="<?= uri("assets/css/contact.css") ?>">
</head>
<body>
	<header class="menu">
		<input type="checkbox" class="burger">
		<nav>
			<ul>
				<li><a href="<?= uri("?p=home") ?>">Home</a></li>
				<li><a href="<?= uri("?p=boutique") ?>">Boutique</a></li>
				<?php if($connect): ?>
					<li><a href="<?= uri("?p=purchase") ?>">Bon de commande</a></li>
					<li><a href="<?= uri("?p=profil") ?>">Profil</a></li>
					<li><a href="<?= uri("?p=deconnect") ?>">Déconnexion</a></li>
				<?php else: ?>
					<li><a href="<?= uri("?p=login") ?>">Connexion</a></li>
				<?php endif; ?> 
				<li><a href="<?= uri("?p=register") ?>">Inscription</a></li>
				<li><a href="<?= uri("?p=contact") ?>">Contact</a></li>
			</ul>
		</nav>
	</header>