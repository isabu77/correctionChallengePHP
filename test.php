<?php
$test = 'test';

var_dump($test);

function bidon()
{
	global $test;
	var_dump($test);
}

bidon();