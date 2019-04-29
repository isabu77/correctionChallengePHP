<?php												//modif apres journé
require_once 'includes/function.php';				//modif apres journée
$user = userOnly();									//verifie que user est connecté
													//modif apres journée
if(!isset($_GET["id"])){							//verifie que id est dans les param
	header('location: '.uri("profil.php"));			//modif apres journée
	exit();											//modif apres journée
}													//modif apres journée
$id = (int)$_GET["id"];								//force get[id] en interger
$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);//modif apres journée
$sql = "SELECT * FROM `orders` WHERE id = ?";		//modif apres journée
$statement = $pdo->prepare($sql);					//modif apres journée
$statement->execute([$id]);							//modif apres journée
$order = $statement->fetch();						//modif apres journée
													//modif apres journée
if(!$order || $order['id_user']!=$user["id_user"]){	//verifie que la commande correspond bien à l'utilisateur
	header('location: '.uri("profil.php"));			//modif apres journée
	exit();											//modif apres journée
}													//modif apres journée
$sql = "SELECT * FROM `beer`";						//modif apres journée
$statement = $pdo->prepare($sql);					//modif apres journée
$statement->execute();								//modif apres journée
$results = $statement->fetchAll();					//recupère toutes les biere
foreach ($results as  $value) {						//modif apres journée
	$beers[$value["id"]] = $value;					//cree un tableau avec en cle les id des bières
}													//modif apres journée
$lines = unserialize($order["ids_product"]);		//desérialise la case ids_product
$priceTTC = 0;										//verif sur l'integrité de la commande
foreach ($lines as $value) {						//modif apres journée
	$priceTTC += $value["price"]*$value["qty"]*$tva;//modif apres journée
}		
													//modif apres journée
if((string)$priceTTC!==$order["priceTTC"]){			//modif apres journée
	header('location: '.uri("profil.php"));			//dire de contacté l'administrateur
	exit();											//modif apres journée
}													//modif apres journée
include 'includes/header.php';						//modif apres journée
?>
<h1 class="titreduhaut">Confirmation de commande</h1>
<section id="commandSection">
	<table>
		<thead>
			<tr>
				<th>Nomination</th>
				<th>Prix HT</th>
				<th>Prix TTC</th>
				<th>Quantité</th>
				<th>Total TTC</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach(/*$commande as $key => $value*/$lines as $key => $value ) : ?>
				<tr>
					<td><?= $beers[$key]["title"] ?></td>
					<td><?= number_format($value["price"], 2, ',', '.'); ?>€</td>
					<td><?= number_format($value["price"]*$tva, 2, ',', '.');  ?>€</td>
					<td><?= $value["qty"] ?></td>
					<td><?= number_format($value["price"]*$value["qty"]*$tva, 2, ',', '.'); ?>€</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td><strong>Total TTC</strong></td>
				<td></td>
				<td></td>
				<td></td>
				<td><strong><?= number_format($order["priceTTC"], 2, ',', '.'); ?>€</strong></td>
			</tr>
		</tbody>
	</table>
	<p style="text-align: center;">Celle-ci vous sera livrée au <?= $user["address"] ?> <?= $user["zipCode"] ?> <?= $user['city'] ?> sous deux jours</p>
		<p style="text-align:center;">
			<small>Si vous ne réglez pas sous 10 jours, le prix de votre commande sera majorée.(25%/jours de retard)</small>
		</p>
</section>
<?php
	include 'includes/footer.php';//modif apres journée


