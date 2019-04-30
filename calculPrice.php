<?php

require_once('includes/function.php');
$user = userOnly();

if(isset($user['mail'])) {
	$sql = "SELECT * FROM `beer`";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$statement = $pdo->prepare($sql);
	$statement->execute(); 

	$beerArray = $statement->fetchAll();

	$beerTotal = [];
	foreach ($beerArray as $key => $beer) {
		$beerTotal[$beer['id']]= $beer;
	}

	$priceTTC = 0;
	foreach($_POST['qty'] as $key => $valueQty) { //on boucle sur le tableau $_POST["qty"]
		if($valueQty > 0) {
			$price = $beerTotal[$key]['price']; 
			$qty[$key] = ['qty' => $valueQty, "price"=>$price];
			$priceTTC += $valueQty * $price * $tva;
		}
	}

	$serialCommande = serialize($qty); //On convertit le tableau $qty en String pour 												l'envoyer en bdd plus tard.

	$orders = [":id_user"=>(int)$user['id_user'], ":ids_product"=>$serialCommande, ":priceTTC"=>$priceTTC];

	$sql = "INSERT INTO `orders` (`id_user`,`ids_product`,`priceTTC`) VALUES (:id_user, :ids_product, :priceTTC)";

	$statement = $pdo->prepare($sql);
	$statement->execute($orders);

	$id = $pdo->lastInsertId(); //On recupère l'id de la dernière insertion en bdd

	header('location: '.uri("confirmationDeCommande.php?id=".$id));
	exit();
}