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
			<?php foreach($commande as $key => $value) : ?>
				<tr>
					<td><?= $value[0] ?></td>
					<td><?= number_format($value[3], 2, ',', '.'); ?>€</td>
					<td><?= number_format($value[3]*$tva, 2, ',', '.');  ?>€</td>
					<td><?= $value[4] ?></td>
					<td><?= number_format($value[5]*$tva, 2, ',', '.'); ?>€</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td><strong>Total TTC</strong></td>
				<td></td>
				<td></td>
				<td></td>
				<td><strong><?= number_format($totalTTC * $tva, 2, ',', '.'); ?>€</strong></td>
			</tr>
		</tbody>
	</table>
	<p style="text-align: center;">Celle-ci vous sera livrée au <?= $_POST['address'] ?> <?= $_POST['zipcode'] ?> <?= $_POST['city'] ?> sous deux jours</p>
		<p style="text-align:center;">
			<small>Si vous ne réglez pas sous 10 jours, le prix de votre commande sera majorée.(25%/jours de retard)</small>
		</p>
</section>