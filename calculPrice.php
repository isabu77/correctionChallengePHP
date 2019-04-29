<?php
require_once('includes/function.php');
$user = userOnly();

if(isset($user['mail']) ) {
	$sql = "SELECT * FROM `beer`";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$statement = $pdo->prepare($sql);
	$statement->execute(); 

	$beerArray = $statement->fetchAll();

	$beerTotal= [];																//modif apres journée
	foreach ($beerArray as $key => $value) {									//modif apres journée
		$beerTotal[$value['id']]=$value;										//modif apres journée
	}																			//modif apres journée

	//$i = 0;  																	//modif apres journée
	$priceTTC = 0;
	 foreach($_POST['qty'] as $key => $value) {									//modif apres journée
	 	if($value > 0) {
	 		$price = $beerTotal[$key]["price"]; 								//modif apres journée
	 		//$qty[$key] = ["qty"=>$value, "price"=>$beerTotal[$i]['price']]; 	//modif apres journéev
	 		$qty[$key] = ["qty"=>$value, "price"=>$price]; 						//modif apres journéev
		//$priceTTC += $value * $beerTotal[$i]['price']; 						//modif apres journée
		$priceTTC += $value * $price * $tva; 									//modif apres journée
	 	}
	 	//$i++; 																//modif apres journée
	 }
	$serialCommande = serialize($qty);//modif apres journée
	$orders = [":id_user"=>(int)$user['id_user'], ":ids_product"=>$serialCommande, "priceTTC"=>$priceTTC];
	$sql = "INSERT INTO `orders` (`id_user`,`ids_product`,`priceTTC`) VALUES (:id_user, :ids_product, :priceTTC)"; //modif apres journée


	$statement = $pdo->prepare($sql);											//modif apres journée
	$statement->execute($orders);												//modif apres journée
	$id = $pdo->lastInsertId();
	header('location: '.uri("confirmationDeCommande.php?id=".$id));				//modif apres journée
	exit();																		//modif apres journée
	//var_dump($serialCommande);//modif apres journée
 	/* [id_user, [id(beer)=> [qty, prix] ...],  pttc] */
	//var_dump(unserialize($serialCommande));//modif apres journée
	//die();//modif apres journée
}