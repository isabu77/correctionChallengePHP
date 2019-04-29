<?php
require_once 'ressources/donnees.php';
require_once 'includes/function.php';

if(!file_exists ('ressources/lock.php')){
	$sql = "INSERT INTO
			`beer` (`title`, `img`, `content`, `price`)
			VALUES (:title, :img, :content, :price)";

	$pdo = getDB($dbuser, $dbpassword, $dbhost,$dbname);
	$statement = $pdo->prepare($sql);
	foreach ($beerArray as $value) {
		$statement->execute([
		':title'	=> $value[0],
		':img'		=> $value[1],
		':content'	=> $value[2],
		':price'	=> $value[3]
	]);
	}
	//cree fichier ressources/lock.php
	fopen('ressources/lock.php', 'w');
	echo "données insérées";
}else{
	echo "aucune modification";
}