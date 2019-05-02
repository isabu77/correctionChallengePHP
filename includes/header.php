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
				<li><a href="<?= uri() ?>">Home</a></li>
				<li><a href="<?= uri("boutique.php") ?>">Boutique</a></li>
				<?php if($connect): ?>
					<li><a href="<?= uri("purchase_order.php") ?>">Bon de commande</a></li>
					<li><a href="<?= uri("profil.php") ?>">profil</a></li>
					<li><a href="<?= uri("index.php?deconnect") ?>">deconnexion</a></li>
				<?php else: ?>
					<li><a href="<?= uri("login.php") ?>">Connexion</a></li>
				<?php endif; ?> 
				<li><a href="<?= uri("userAction.php") ?>">Inscription</a></li>
				<li><a href="<?= uri("contact.php") ?>">Contact</a></li>
			</ul>
		</nav>
	</header>