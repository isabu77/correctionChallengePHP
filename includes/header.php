<?php
require_once 'function.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Bread Beer Shop</title>
	<link rel="stylesheet" type="text/css" href="<?= uri("assets/css/styles.css") ?>">
</head>
<body>
	<header class="menu">
		<input type="checkbox" class="burger">
		<nav>
			<ul>
				<li><a href="<?= uri() ?>">Home</a></li>
				<li><a href="<?= uri("boutique.php") ?>">Boutique</a></li>
				<li><a href="<?= uri("purchase_order.php") ?>">Bon de commande</a></li>
				<li><a href="#">A propos</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
		</nav>
	</header>