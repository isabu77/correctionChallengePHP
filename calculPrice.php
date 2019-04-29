<?php
require_once('includes/function.php');
$user = userOnly();

if(isset($user['mail']) ) {
	$sql = "SELECT * FROM `beer`";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$statement = $pdo->prepare($sql);
	$statement->execute(); 

	$beerArray = $statement->fetchAll();


	$beerTotal = $beerArray;

	$i = 0;
	$priceTTC = 0;
	 foreach($_POST['qty'] as $key => $value) {
	 	if($value > 0) {
	 		$qty[$key] = ["qty"=>$value, "price"=>$beerTotal[$i]['price']];
		$priceTTC += $value * $beerTotal[$i]['price'];
	 	}
	 	$i++;
	 }


	$commande = [$user['id_user'], $qty, $priceTTC];
	var_dump(serialize($commande));
 			/* id_user [id(beer)=> [qty, prix] ...]  pttc
	*/
	//var_dump($commande);
	die();


	// $totalTTC = 0;
	// foreach ($commande as $key => $value) {
	// 	$totalTTC += $value[5];
	// }
}

include 'includes/header.php';

include 'confirmationDeCommande.php'; 
include 'includes/footer.php';