<?php
require_once('includes/function.php');
	//require_once('ressources/donnees.php');
	$sql = "SELECT * FROM `beer`";
	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$statement = $pdo->prepare($sql);
	$statement->execute(); 

	$beerArray = $statement->fetchAll();

//	include 'includes/header.php';
?>

<h1 class="titreduhaut">Bread Beer Shop - Nos Produits</h1>
<section id="boutiques">
	<?php foreach($beerArray as $value) : ?>
		<article class="bieres">
			<h2><?= $value['title']; ?></h2>
			<div><img src="<?= $value['img']; ?>" alt="<?= $value['title']; ?>" /></div>
			<p><?= $value['content']; ?></p>
			<p class="price"><?=    (String)number_format($value['price']*$tva,2,',',' ').'€'; ?></p>
		</article>
	<?php endforeach; ?>
</section>

<?php
//	include 'includes/footer.php'; 