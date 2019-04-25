<?php
	require_once('ressources/donnees.php');
	include 'includes/header.php';
?>

<h1 class="titreduhaut">Nos Produits</h1>
<section id="boutiques">
	<?php foreach($beerArray as $value) : ?>
		<article class="bieres">
			<h2><?= $value[0]; ?></h2>
			<div><img src="<?= $value[1]; ?>" alt="<?= $value[0]; ?>" /></div>
			<p><?= $value[2]; ?></p>
			<p class="price"><?= $value[3]; ?></p>
		</article>
	<?php endforeach; ?>
</section>

<?php
	include 'includes/footer.php'; ?>