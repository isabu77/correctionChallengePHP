<?php
	require_once('includes/function.php');
	$user = userOnly();

	if(!isset($_GET['id'])) {
		header('location:'.uri("profil.php"));
		exit();
	}
	$id = (int)$_GET['id']; //On "CAST"(convertit) $_GET['id'] en Integer

	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$sql = "SELECT * FROM orders WHERE id = ?";
	$statement = $pdo->prepare($sql);
	$statement->execute([$id]);
	$order = $statement->fetch();

	if(!$order || $order['id_user'] != $user["id_user"]) { //On vérifie l'id de l'utilisateur
		header('location: '.uri("profil.php"));				//Et l'existence de la commande
		exit();
	}

	$sql = "SELECT * FROM beer";
	$statement = $pdo->prepare($sql);
	$statement->execute();
	$results = $statement->fetchAll();

	foreach($results as $result) {
		$beers[$result["id"]] = $result;
	}
	//var_dump($beers);die;

	$lines = unserialize($order['ids_product']); //Rétablis le tableau à sa forme originale
	$priceTTC = 0;
	foreach( $lines as $line) {
		$priceTTC += ($line["price"] * $line["qty"]) * $tva;
	}

	if((string)$priceTTC !== $order["priceTTC"]) { //On CAST $priceTTC en String pour 														comparaison avec $order["priceTTC"]
		header('location:'.uri('profil.php'));
		exit();
	}
	include 'includes/header.php';
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
			<?php foreach($lines as $key => $line ) : ?>
				<tr>
					<td><?= $beers[$key]["title"] ?></td>
					<td><?= number_format($line["price"], 2, ',', '.'); ?>€</td>
					<td><?= number_format($line["price"]*$tva, 2, ',', '.');  ?>€</td>
					<td><?= $line["qty"] ?></td>
					<td><?= number_format($line["price"]*$line["qty"]*$tva, 2, ',', '.'); ?>€</td>
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
			<small>Si vous ne réglez pas sous 10 jours, le prix de votre commande sera majoré.(25%/jour de retard)</small>
		</p>
</section>
<?php
	include 'includes/footer.php';


