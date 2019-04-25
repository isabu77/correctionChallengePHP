<?php require_once('ressources/donnees.php');
		include 'includes/header.php'; ?>
	<form method="post" action="/includes/confirmationCommande.php">
		<div class="form_row">
			<div class="form">
				<label>NOM</label>
				<input type="text" name="lastname" required/>
			</div>
			<div class="form">
				<label>PRENOM</label>
				<input type="text" name="firstname" required/>
			</div>
		</div>
		<div class="form">
			<label>ADRESSE</label>
			<input type="text" name="address" required/>
		</div>
		<div class="form_row">
			<div class="form">
				<label>Code Postal</label>
				<input type="text" name="zipcode" required/>
			</div>
			<div class="form">
				<label>VILLE</label>
				<input type="text" name="city" required/>
			</div>
		</div>
		<div class="form">
			<label>PAYS</label>
			<input type="text" name="country" required/>
		</div>
		<div class="form_row">
			<div class="form">
				<label>TEL</label>
				<input type="tel" name="tel" required/>
			</div>
			<div class="form">
				<label>MAIL</label>
				<input type="mail" name="mail" required/>
			</div>
		</div>
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
				<?php foreach($beerArray as $key => $value) : ?>
					<tr>
						<td><?= $value[0] ?></td>
						<td id="PHT_<?= $key ?>"><?= number_format($value[3], 2, ',', '.') ?>€</td>
						<td id="PTTC_<?= $key ?>"><?= number_format($value[3]*$tva, 2, ',', '.') ?>€</td>
						<td><input type="number" min="0" name="qty[]" value="0" oninput="calcPrice(this, <?= $key ?>, <?= $value[3] ?>);" /></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<button type="submit">COMMANDER</button>
	</form>

<?php include 'includes/footer.php'; ?>
