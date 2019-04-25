<?php

if(isset($_POST['mail'])) {
	include ('../ressources/donnees.php');

	$beerTotal = $beerArray;

	$commande = [];
	foreach($_POST['qty'] as $key => $value) {
		if($value > 0) {
			$beerTotal[$key][3] = $beerTotal[$key][3]*$value;
			$beerTotal[$key][4] = $value;
			array_push($commande, $beerTotal[$key]);
		}
	}

	$totalTTC = 0;
	foreach ($commande as $key => $value) {
		$totalTTC += $value[3];
	}
}

include '../includes/header.php'; ?>
<h1 class="titreduhaut">Confirmation de commande</h1>
<section id="commandSection">
	<table>
		<thead>
			<tr>
				<th>Nomination</th>
				<th>Prix HT</th>
				<th>Prix TTC</th>
				<th>Quantité</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($commande as $key => $value) : ?>
				<tr>
					<td><?= $value[0] ?></td>
					<td><?= number_format($value[3], 2, ',', '.'); ?>€</td>
					<td><?= number_format($value[3]*$tva, 2, ',', '.');  ?>€</td>
					<td><?= $value[4] ?></td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td>Total TTC</td>
				<td></td>
				<td></td>
				<td><?= $totalTTC ?>€</td>
			</tr>
		</tbody>
	</table>
	<p style="text-align: center;">Celle-ci vous sera livrée au <?= $_POST['address'] ?> <?= $_POST['zipcode'] ?> <?= $_POST['city'] ?> sous deux jours</p>
		<p style="text-align:center;">
			<small>Si vous ne réglez pas sous 10 jours, le prix de votre commande sera majorée.(25%/jours de retard)</small>
		</p>
</section>
<?php include '../includes/footer.php'; ?>